<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationsRequest extends FormRequest
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
        $id = $this->route('location') ?? $this->route('id');

        return [
            'name' => 'required|string|max:255|unique:locations,name,' . $id,
            'branch_id' => 'required|exists:branches,id',
            'type' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lokasi wajib diisi',
            'name.unique' => 'Nama lokasi sudah digunakan',
            'branch_id.required' => 'Cabang wajib dipilih',
            'branch_id.exists' => 'Cabang yang dipilih tidak valid',
            'type.required' => 'Tipe lokasi wajib dipilih',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama lokasi',
            'branch_id' => 'cabang',
            'type' => 'tipe lokasi',
        ];
    }
}
