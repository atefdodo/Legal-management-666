<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyDocumentRequest extends FormRequest
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
}
