<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchasesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'branch_id' => 'required|exists:branches,id',
            'location_id' => 'required|exists:locations,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,completed,cancelled',
            'notes' => 'nullable|string|max:1000',

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',

            // minimal salah satu qty harus diisi
            'items.*.qty_large' => 'nullable|integer|min:0|required_without:items.*.qty_small',
            'items.*.qty_small' => 'nullable|integer|min:0|required_without:items.*.qty_large',

            // minimal salah satu harga beli harus diisi
            'items.*.purchase_price_large' => 'nullable|numeric|min:0|required_without:items.*.purchase_price_small',
            'items.*.purchase_price_small' => 'nullable|numeric|min:0|required_without:items.*.purchase_price_large',

            // minimal salah satu harga jual harus diisi
            'items.*.selling_price_large' => 'nullable|numeric|min:0|required_without:items.*.selling_price_small',
            'items.*.selling_price_small' => 'nullable|numeric|min:0|required_without:items.*.selling_price_large',
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

            'items.*.qty_large.required_without' => 'Isi jumlah besar atau jumlah kecil minimal salah satu',
            'items.*.qty_small.required_without' => 'Isi jumlah kecil atau jumlah besar minimal salah satu',

            'items.*.purchase_price_large.required_without' => 'Isi harga beli besar atau harga beli kecil minimal salah satu',
            'items.*.purchase_price_small.required_without' => 'Isi harga beli kecil atau harga beli besar minimal salah satu',

            'items.*.selling_price_large.required_without' => 'Isi harga jual besar atau harga jual kecil minimal salah satu',
            'items.*.selling_price_small.required_without' => 'Isi harga jual kecil atau harga jual besar minimal salah satu',
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
