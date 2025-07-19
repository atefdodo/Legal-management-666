<?php

namespace App\Http\Controllers;

use App\Models\RentalContract;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRentalContractRequest;
use App\Http\Requests\UpdateRentalContractRequest;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\SimpleType\Jc;

class RentalContractController extends Controller
{
    public function index()
    {
        return view('rental_contracts.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = RentalContract::latest();
            return DataTables::of($data)
                ->addColumn('select', fn($row) => "<input type='checkbox' class='doc-select' value='{$row->id}'>")
                ->addColumn('action', fn($row) => view('rental_contracts.partials.actions', compact('row'))->render())
                ->rawColumns(['select', 'action'])
                ->make(true);
        }
    }

    public function store(StoreRentalContractRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('document_image_path')) {
            $validated['document_image_path'] = $request->file('document_image_path')->store('contracts');
        }

        RentalContract::create($validated);
        return response('created');
    }

    public function edit(RentalContract $rental_contract)
    {
        return response()->json($rental_contract);
    }

    public function update(UpdateRentalContractRequest $request, RentalContract $rental_contract)
    {
        $validated = $request->validated();

        if ($request->hasFile('document_image_path')) {
            $validated['document_image_path'] = $request->file('document_image_path')->store('contracts');
        }

        $rental_contract->update($validated);
        return response('updated');
    }

    public function destroy(RentalContract $rental_contract)
    {
        $rental_contract->delete();
        return response()->json(['success' => true]);
    }

    public function show(RentalContract $rental_contract)
    {
        return view('rental_contracts.partials.view-body', compact('rental_contract'));
    }

    public function exportPdf(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        $contracts = RentalContract::whereIn('id', $ids)->get();

        $html = View::make('rental_contracts.exports.pdf', compact('contracts'))->render();

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

        return response($mpdf->Output('rental_contracts.pdf', 'S'), 200)->header('Content-Type', 'application/pdf');
    }

    public function exportDocx(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        $contracts = RentalContract::whereIn('id', $ids)->get();

        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language('ar-SA'));

        $section = $phpWord->addSection();

        $section->addText('عقود الإيجار', ['bold' => true, 'size' => 16, 'name' => 'Arial'], ['rtl' => true, 'alignment' => Jc::CENTER]);

        $table = $section->addTable([
            'alignment' => Jc::RIGHT,
            'rtl' => true,
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
        ]);

        $table->addRow();
        $table->addCell(2000)->addText('قيمة الإيجار', ['bold' => true], ['rtl' => true]);
        $table->addCell(2000)->addText('نهاية الإيجار', ['bold' => true], ['rtl' => true]);
        $table->addCell(2000)->addText('بداية الإيجار', ['bold' => true], ['rtl' => true]);
        $table->addCell(2000)->addText('تاريخ العقد', ['bold' => true], ['rtl' => true]);
        $table->addCell(2000)->addText('المستأجر', ['bold' => true], ['rtl' => true]);
        $table->addCell(2000)->addText('المؤجر', ['bold' => true], ['rtl' => true]);
        $table->addCell(2000)->addText('المكان', ['bold' => true], ['rtl' => true]);

        foreach ($contracts as $c) {
            $table->addRow();
            $table->addCell(2000)->addText($c->rent_amount . ' ج.م', [], ['rtl' => true]);
            $table->addCell(2000)->addText($c->end_date, [], ['rtl' => true]);
            $table->addCell(2000)->addText($c->start_date, [], ['rtl' => true]);
            $table->addCell(2000)->addText($c->contract_date, [], ['rtl' => true]);
            $table->addCell(2000)->addText($c->lessee_name, [], ['rtl' => true]);
            $table->addCell(2000)->addText($c->lessor_name, [], ['rtl' => true]);
            $table->addCell(2000)->addText($c->rental_location, [], ['rtl' => true]);
        }

        $fileName = 'rental_contracts.docx';
        $tempFile = storage_path("app/{$fileName}");
        $phpWord->save($tempFile, 'Word2007');

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
}
