<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'issuance_date' => 'required|date',
            'issuing_authority' => 'required|string|max:255',
            'renewal_date' => 'nullable|date',
            'document_image_path' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5120',
        ];
    }

    public function messages(): array
{
    return [
        'name.required' => 'يرجى إدخال اسم المستند.',
        'name.string' => 'اسم المستند يجب أن يكون نصًا.',
        'name.max' => 'اسم المستند لا يجب أن يتجاوز 255 حرفًا.',
        'issuance_date.required' => 'يرجى إدخال تاريخ الإصدار.',
        'issuance_date.date' => 'تاريخ الإصدار يجب أن يكون تاريخًا صحيحًا.',
        'issuing_authority.required' => 'يرجى إدخال جهة الإصدار.',
        'issuing_authority.string' => 'جهة الإصدار يجب أن تكون نصًا.',
        'issuing_authority.max' => 'جهة الإصدار لا يجب أن تتجاوز 255 حرفًا.',
        'renewal_date.date' => 'تاريخ التجديد يجب أن يكون تاريخًا صحيحًا.',
        'document_image_path.file' => 'يجب تحميل ملف صحيح.',
        'document_image_path.mimes' => 'الملف يجب أن يكون بصيغة: PDF أو JPEG أو JPG أو PNG.',
        'document_image_path.max' => 'حجم الملف لا يجب أن يتجاوز 5 ميجابايت.',
    ];
}

}
