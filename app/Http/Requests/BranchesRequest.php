<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchesRequest extends FormRequest
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
        $id = $this->route('branch') ?? $this->route('id');

        return [
            'code' => 'required|string|max:20|unique:branches,code,' . $id,
            'name' => 'required|string|max:255|unique:branches,name,' . $id,
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode cabang wajib diisi',
            'code.unique' => 'Kode cabang sudah digunakan',
            'code.max' => 'Kode cabang maksimal 20 karakter',
            'name.required' => 'Nama cabang wajib diisi',
            'name.unique' => 'Nama cabang sudah digunakan',
            'address.required' => 'Alamat cabang wajib diisi',
            'city.required' => 'Kota wajib diisi',
            'province.required' => 'Provinsi wajib diisi',
            'postal_code.required' => 'Kode pos wajib diisi',
            'status.required' => 'Status cabang wajib dipilih',
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode cabang',
            'name' => 'nama cabang',
            'address' => 'alamat cabang',
            'city' => 'kota',
            'province' => 'provinsi',
            'postal_code' => 'kode pos',
            'phone' => 'telepon',
            'status' => 'status',
        ];
    }
}
