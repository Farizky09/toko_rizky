<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductsRequest extends FormRequest
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
        $id = $this->route('product') ?? $this->route('id');

        return [
            'code' => 'required|string|max:50|unique:products,code,' . $id,
            'name' => 'required|string|max:255|unique:products,name,' . $id,
            'category_id' => 'required|exists:categories,id',
            'unit_large_id' => 'required|exists:unit_larges,id',
            'unit_small_id' => 'required|exists:unit_smalls,id',
            'conversion' => 'required|numeric|min:0.01',
            'min_stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode produk wajib diisi',
            'code.unique' => 'Kode produk sudah digunakan',
            'name.required' => 'Nama produk wajib diisi',
            'name.unique' => 'Nama produk sudah digunakan',
            'category_id.required' => 'Kategori wajib dipilih',
            'category_id.exists' => 'Kategori yang dipilih tidak valid',
            'unit_large_id.required' => 'Satuan besar wajib dipilih',
            'unit_large_id.exists' => 'Satuan besar yang dipilih tidak valid',
            'unit_small_id.required' => 'Satuan kecil wajib dipilih',
            'unit_small_id.exists' => 'Satuan kecil yang dipilih tidak valid',
            'conversion.required' => 'Konversi wajib diisi',
            'conversion.numeric' => 'Konversi harus berupa angka',
            'conversion.min' => 'Konversi minimal 0.01',
            'min_stock.required' => 'Stok minimum wajib diisi',
            'min_stock.integer' => 'Stok minimum harus berupa bilangan bulat',
            'min_stock.min' => 'Stok minimum minimal 0',
            'status.required' => 'Status produk wajib dipilih',
            'status.in' => 'Status produk tidak valid',
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode produk',
            'name' => 'nama produk',
            'category_id' => 'kategori',
            'unit_large_id' => 'satuan besar',
            'unit_small_id' => 'satuan kecil',
            'conversion' => 'konversi',
            'min_stock' => 'stok minimum',
            'status' => 'status',
            'description' => 'deskripsi',
        ];
    }
}
