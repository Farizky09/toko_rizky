<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('category') ?? $this->route('id');

        return [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi',
            'name.string' => 'Nama kategori harus berupa teks',
            'name.max' => 'Nama kategori maksimal 255 karakter',
            'name.unique' => 'Nama kategori sudah digunakan',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama kategori',
        ];
    }
}
