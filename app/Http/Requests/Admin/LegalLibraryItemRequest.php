<?php

namespace App\Http\Requests\Admin;

use App\Models\LegalLibraryItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LegalLibraryItemRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('slug')) {
            $this->merge(['slug' => str($this->input('slug'))->trim()->lower()->toString()]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('legalLibrary');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('legal_library_items', 'slug')->ignore($item?->id),
            ],
            'category' => ['required', Rule::in(array_keys(LegalLibraryItem::CATEGORIES))],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.regex' => 'الرابط يجب أن يحتوي أحرفاً إنجليزية صغيرة أو أرقاماً وشرطة (-) فقط، مثل civil-law-2026.',
            'pdf_file.mimes' => 'يجب أن يكون الملف بصيغة PDF فقط.',
            'pdf_file.max' => 'حجم ملف PDF يجب ألا يتجاوز 10MB.',
        ];
    }
}
