<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRentalContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // يمكن تخصيصه لاحقًا لتحديد الصلاحيات
    }

    public function rules(): array
    {
        return [
            'lessor_name'         => ['required', 'string', 'max:255'],
            'lessee_name'         => ['required', 'string', 'max:255'],
            'contract_date'       => ['required', 'date'],
            'start_date'          => ['required', 'date', 'after_or_equal:contract_date'],
            'end_date'            => ['required', 'date', 'after_or_equal:start_date'],
            'rental_location'     => ['required', 'string', 'max:500'],
            'rent_amount'         => ['required', 'numeric', 'min:0'],
            'document_image_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB
        ];
    }

    public function attributes(): array
    {
        return [
            'lessor_name'         => 'اسم المؤجر',
            'lessee_name'         => 'اسم المستأجر',
            'contract_date'       => 'تاريخ تحرير العقد',
            'start_date'          => 'تاريخ بداية الإيجار',
            'end_date'            => 'تاريخ نهاية الإيجار',
            'rental_location'     => 'محل الإيجار',
            'rent_amount'         => 'قيمة الإيجار الشهري',
            'document_image_path' => 'صورة العقد',
        ];
    }

    public function messages(): array
    {
        return [
            'required'         => 'حقل :attribute مطلوب.',
            'date'             => 'حقل :attribute يجب أن يكون تاريخاً صالحاً.',
            'after_or_equal'   => 'حقل :attribute يجب أن يكون بعد أو يساوي :other.',
            'numeric'          => 'حقل :attribute يجب أن يكون رقمًا.',
            'min'              => 'حقل :attribute يجب أن يكون على الأقل :min.',
            'max'              => 'حقل :attribute يجب ألا يزيد عن :max كيلوبايت.',
            'file'             => 'حقل :attribute يجب أن يكون ملفًا.',
            'mimes'            => 'حقل :attribute يجب أن يكون من نوع: :values.',
        ];
    }
}
