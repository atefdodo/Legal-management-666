<?php

namespace App\Http\Controllers;

use App\Models\CompanyDocument;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCompanyDocumentRequest;
use App\Http\Requests\UpdateCompanyDocumentRequest;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\SimpleType\Jc;

class CompanyDocumentController extends Controller
{
    // =============================
    // 📄 عرض الصفحة الرئيسية
    // =============================
    public function index()
    {
        return view('company_documents.index');
    }

    // =============================
    // 📋 Ajax DataTable
    // =============================
    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        $data = CompanyDocument::query();

        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && ($search = $request->get('search')['value'])) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('issuing_authority', 'like', "%{$search}%")
                            ->orWhere('issuance_date', 'like', "%{$search}%")
                            ->orWhere('renewal_date', 'like', "%{$search}%");
                    });
                }
            })
            ->addColumn('status', fn($row) => $this->getDocumentStatus($row))
            ->filterColumn('status', function ($query, $keyword) {
                $today = now()->startOfDay();
                $thirtyDaysFromNow = $today->copy()->addDays(30);

                switch ($keyword) {
                    case 'غير محدد':
                        $query->whereNull('renewal_date');
                        break;
                    case 'منتهي':
                        $query->whereNotNull('renewal_date')->whereDate('renewal_date', '<', $today);
                        break;
                    case 'قارب على الانتهاء':
                        $query->whereNotNull('renewal_date')
                              ->whereDate('renewal_date', '>=', $today)
                              ->whereDate('renewal_date', '<=', $thirtyDaysFromNow);
                        break;
                    case 'ساري':
                        $query->whereNotNull('renewal_date')->whereDate('renewal_date', '>', $thirtyDaysFromNow);
                        break;
                }
            })
            ->addColumn('select', fn($row) =>
                "<input type='checkbox' class='form-check-input doc-select' value='{$row->id}'>"
            )
            ->addColumn('action', fn($row) =>
                view('company_documents.partials.actions', compact('row'))->render()
            )
            ->rawColumns(['select', 'action'])
            ->make(true);
    }

    // =============================
    // ✅ حالة المستند (مساعد)
    // =============================
    private function getDocumentStatus($document)
    {
        if (!$document->renewal_date) return 'غير محدد';

        $renewal = \Carbon\Carbon::parse($document->renewal_date)->startOfDay();
        $today = now()->startOfDay();
        $in30Days = $today->copy()->addDays(30);

        return match (true) {
            $renewal->lt($today) => 'منتهي',
            $renewal->gte($today) && $renewal->lte($in30Days) => 'قارب على الانتهاء',
            default => 'ساري',
        };
    }

    // =============================
    // ➕ إنشاء مستند جديد
    // =============================
    public function store(StoreCompanyDocumentRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('document_image_path')) {
            $validated['document_image_path'] = $request->file('document_image_path')->store('docs');
        }

        CompanyDocument::create($validated);
        return response('created');
    }

    // =============================
    // ✏️ تعديل مستند
    // =============================
    public function edit(CompanyDocument $company_document)
    {
        return response()->json($company_document);
    }

    public function update(UpdateCompanyDocumentRequest $request, CompanyDocument $company_document)
    {
        $validated = $request->validated();

        if ($request->hasFile('document_image_path')) {
            $validated['document_image_path'] = $request->file('document_image_path')->store('docs');
        }

        $company_document->update($validated);
        return response('updated');
    }

    // =============================
    // 🗑️ حذف مستند
    // =============================
    public function destroy(CompanyDocument $company_document)
    {
        $company_document->delete();
        return response()->json(['success' => true]);
    }

    // =============================
    // 👁️ عرض مستند داخل مودال
    // =============================
    public function show(CompanyDocument $company_document)
    {
        return view('company_documents.partials.view-body', compact('company_document'));
    }

    // =============================
    // 📤 تصدير PDF
    // =============================
    public function exportPdf(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        $documents = CompanyDocument::whereIn('id', $ids)->get();

        $html = View::make('company_documents.exports.pdf', compact('documents'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'default_font' => 'amiri',
            'default_font_size' => 12,
            'format' => 'A4',
            'orientation' => 'P',
            'directionality' => 'rtl',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->WriteHTML($html);

        return response($mpdf->Output('company_documents.pdf', 'S'), 200)
            ->header('Content-Type', 'application/pdf');
    }

    // =============================
    // 📤 تصدير DOCX
    // =============================
    public function exportDocx(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        $docs = CompanyDocument::whereIn('id', $ids)->get();

        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language('ar-SA'));

        $section = $phpWord->addSection();

        // العنوان
        $section->addText('مستندات الشركة', [
            'bold' => true,
            'size' => 16,
            'name' => 'Arial',
        ], [
            'rtl' => true,
            'alignment' => Jc::CENTER,
        ]);

        // الجدول
        $table = $section->addTable([
            'alignment' => Jc::RIGHT,
            'rtl' => true,
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
        ]);

        $table->addRow();
        $table->addCell(2000)->addText('تاريخ التجديد', ['bold' => true], ['rtl' => true]);
        $table->addCell(2000)->addText('جهة الاصدار', ['bold' => true], ['rtl' => true]);
        $table->addCell(2000)->addText('تاريخ الاصدار', ['bold' => true], ['rtl' => true]);
        $table->addCell(2000)->addText('اسم المستند', ['bold' => true], ['rtl' => true]);

        foreach ($docs as $doc) {
            $table->addRow();
            $table->addCell(2000)->addText($doc->renewal_date ?? '—', [], ['rtl' => true]);
            $table->addCell(2000)->addText($doc->issuing_authority ?? '—', [], ['rtl' => true]);
            $table->addCell(2000)->addText($doc->issuance_date ?? '—', [], ['rtl' => true]);
            $table->addCell(2000)->addText($doc->name ?? '—', [], ['rtl' => true]);
        }

        $fileName = 'documents.docx';
        $tempFile = storage_path("app/{$fileName}");
        $phpWord->save($tempFile, 'Word2007');

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
}
