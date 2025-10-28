<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitLargesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('unit_large') ?? $this->route('id');
        return [
            'name' => 'required|string|max:255|unique:unit_larges,name,' . $id,
            'abbreviation' => 'required|string|max:50|unique:unit_larges,abbreviation,' . $id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama satuan besar wajib diisi',
            'name.string' => 'Nama satuan besar harus berupa teks',
            'name.max' => 'Nama satuan besar maksimal 255 karakter',
            'name.unique' => 'Nama satuan besar sudah digunakan',
            'abbreviation.required' => 'Singkatan wajib diisi',
            'abbreviation.string' => 'Singkatan harus berupa teks',
            'abbreviation.max' => 'Singkatan maksimal 50 karakter',
            'abbreviation.unique' => 'Singkatan sudah digunakan',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama satuan besar',
            'abbreviation' => 'singkatan',
        ];
    }
}
