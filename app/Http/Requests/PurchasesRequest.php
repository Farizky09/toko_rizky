<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchasesRequest extends FormRequest
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
        $id = $this->route('purchase') ?? $this->route('id');

        return [
            'branch_id' => 'required|exists:branches,id',
            'location_id' => 'required|exists:locations,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,pending,completed,cancelled',
            'notes' => 'nullable|string|max:1000',

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty_large' => 'required|integer|min:0',
            'items.*.qty_small' => 'required|integer|min:0',
            'items.*.purchase_price_large' => 'required|numeric|min:0',
            'items.*.purchase_price_small' => 'required|numeric|min:0',
            'items.*.selling_price_large' => 'required|numeric|min:0',
            'items.*.selling_price_small' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang wajib dipilih',
            'location_id.required' => 'Lokasi wajib dipilih',
            'supplier_id.required' => 'Supplier wajib dipilih',
            'purchase_date.required' => 'Tanggal pembelian wajib diisi',
            'items.required' => 'Minimal satu item harus ditambahkan',
            'items.*.product_id.required' => 'Produk wajib dipilih',
            'items.*.qty_large.required' => 'Jumlah besar wajib diisi',
            'items.*.qty_small.required' => 'Jumlah kecil wajib diisi',
            'items.*.purchase_price_large.required' => 'Harga beli besar wajib diisi',
            'items.*.purchase_price_small.required' => 'Harga beli kecil wajib diisi',
            'items.*.selling_price_large.required' => 'Harga jual besar wajib diisi',
            'items.*.selling_price_small.required' => 'Harga jual kecil wajib diisi',
        ];
    }

    public function attributes(): array
    {
        return [
            'branch_id' => 'cabang',
            'location_id' => 'lokasi',
            'supplier_id' => 'supplier',
            'purchase_date' => 'tanggal pembelian',
            'items' => 'item pembelian',
            'items.*.product_id' => 'produk',
            'items.*.qty_large' => 'jumlah besar',
            'items.*.qty_small' => 'jumlah kecil',
            'items.*.purchase_price_large' => 'harga beli besar',
            'items.*.purchase_price_small' => 'harga beli kecil',
            'items.*.selling_price_large' => 'harga jual besar',
            'items.*.selling_price_small' => 'harga jual kecil',
        ];
    }
}
