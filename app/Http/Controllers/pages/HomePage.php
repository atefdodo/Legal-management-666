<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyDocument;
use App\Models\RentalContract;
use Carbon\Carbon;

class HomePage extends Controller
{
    /**
     * الصفحة الرئيسية للوحة التحكم (Dashboard)
     */
    public function index()
    {
        // 🔹 إحصائيات المستندات
        $totalDocuments         = CompanyDocument::count();
        $activeDocuments        = CompanyDocument::whereDate('renewal_date', '>', now()->addDays(30))->count();
        $expiringSoonDocuments  = CompanyDocument::whereBetween('renewal_date', [now(), now()->addDays(30)])->count();
        $expiredDocuments       = CompanyDocument::whereDate('renewal_date', '<', now())->count();

        // 🔹 أحدث 3 مستندات قريبة الانتهاء (للتنبيهات أو الكروت)
        $expiringSoonList = CompanyDocument::whereNotNull('renewal_date')
            ->whereDate('renewal_date', '>=', now())
            ->whereDate('renewal_date', '<=', now()->addDays(30))
            ->orderBy('renewal_date', 'asc')
            ->take(3)
            ->get();

        // 🔹 إحصائيات عقود الإيجار
        $totalContracts = RentalContract::count();

        // ✅ عرض الصفحة مع الإحصائيات
        return view('content.pages.pages-home', compact(
            'totalDocuments',
            'activeDocuments',
            'expiringSoonDocuments',
            'expiredDocuments',
            'expiringSoonList',
            'totalContracts'
        ));
    }

    /**
     * دالة AJAX - ترجع المستندات القريبة الانتهاء كـ JSON (تستخدم مع SweetAlert Toast)
     */
    public function expiringToast()
    {
        $docs = CompanyDocument::whereNotNull('renewal_date')
            ->whereDate('renewal_date', '>=', now())
            ->whereDate('renewal_date', '<=', now()->addDays(30))
            ->orderBy('renewal_date')
            ->take(3)
            ->get(['id', 'name', 'renewal_date']);

        // ✅ تنسيق التاريخ بالعربي
        return response()->json(
            $docs->map(function ($doc) {
                return [
                    'id'            => $doc->id,
                    'name'          => $doc->name,
                    'renewal_date'  => Carbon::parse($doc->renewal_date)->locale('ar')->translatedFormat('Y-m-d'),
                ];
            })
        );
    }
}
