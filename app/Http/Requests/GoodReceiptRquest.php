<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodReceiptRquest extends FormRequest
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
        return [
            'purchase_id' => 'required|exists:purchases,id',
            'branch_id' => 'required|exists:branches,id',
            'location_id' => 'required|exists:locations,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'receipt_date' => 'required|date',
            'received_by' => 'required|string|max:255',
            'total_items' => 'required|integer|min:1',
            'total_quantity_large' => 'required|numeric|min:0',
            'total_quantity_small' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.purchase_items_id' => 'required|exists:purchases_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty_received_large' => 'required|numeric|min:0',
            'items.*.qty_received_small' => 'required|numeric|min:0',
            'items.*.qty_rejected_large' => 'nullable|numeric|min:0',
            'items.*.qty_rejected_small' => 'nullable|numeric|min:0',
            'items.*.reject_reason' => 'nullable|string|max:500',
            'items.*.expiry_date' => 'nullable|date',
            'items.*.note' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'purchase_id.required' => 'Purchase harus dipilih',
            'purchase_id.exists' => 'Purchase tidak valid',
            'branch_id.required' => 'Cabang harus dipilih',
            'location_id.required' => 'Lokasi harus dipilih',
            'supplier_id.required' => 'Supplier harus dipilih',
            'receipt_date.required' => 'Tanggal penerimaan harus diisi',
            'received_by.required' => 'Penerima harus diisi',
            'total_items.required' => 'Total item harus diisi',
            'items.required' => 'Minimal satu item harus ditambahkan',
            'items.*.purchase_items_id.required' => 'Item purchase harus dipilih',
            'items.*.product_id.required' => 'Product harus dipilih',
            'items.*.qty_received_large.required' => 'Quantity besar diterima harus diisi',
            'items.*.qty_received_small.required' => 'Quantity kecil diterima harus diisi',
        ];
    }
}
