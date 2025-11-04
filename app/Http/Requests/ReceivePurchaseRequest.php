<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceivePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',

            'items.*' => 'array',

            'items.*.qty_received_large' => 'nullable|numeric|min:0',
            'items.*.qty_received_small' => 'nullable|numeric|min:0',

            'items.*.item_notes' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Tidak ada item yang diterima.',
            'items.*.qty_received_large.numeric' => 'Qty terima (besar) harus angka.',
            'items.*.qty_received_small.numeric' => 'Qty terima (kecil) harus angka.',
        ];
    }

    public function attributes(): array
    {
        return [
            'items.*.qty_received_large' => 'kuantitas diterima (besar)',
            'items.*.qty_received_small' => 'kuantitas diterima (kecil)',
            'items.*.item_notes' => 'catatan item',
        ];
    }
}
