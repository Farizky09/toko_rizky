<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuppliersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('supplier') ?? $this->route('id');
        return [
            'name' => 'required|string|max:255|unique:suppliers,name,' . $id,
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama supplier wajib diisi',
            'name.string' => 'Nama supplier harus berupa teks',
            'name.max' => 'Nama supplier maksimal 255 karakter',
            'name.unique' => 'Nama supplier sudah digunakan',
            'address.string' => 'Alamat harus berupa teks',
            'address.max' => 'Alamat maksimal 500 karakter',
            'phone.string' => 'Nomor telepon harus berupa teks',
            'phone.max' => 'Nomor telepon maksimal 20 karakter',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama supplier',
            'address' => 'alamat',
            'phone' => 'nomor telepon',
        ];
    }
}
