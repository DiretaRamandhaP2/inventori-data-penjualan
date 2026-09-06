<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'inventory_id'     => ['required', 'string', 'exists:inventories,id'],
            'quantity'         => ['required', 'integer', 'min:1'],
            'transaction_date' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    /**
     * Custom validation messages dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'inventory_id.required'          => 'Produk wajib dipilih.',
            'inventory_id.exists'            => 'Produk yang dipilih tidak ditemukan di database.',
            'quantity.required'              => 'Jumlah pembelian wajib diisi.',
            'quantity.integer'               => 'Jumlah pembelian harus berupa bilangan bulat.',
            'quantity.min'                   => 'Jumlah pembelian minimal 1 item.',
            'transaction_date.required'      => 'Tanggal transaksi wajib diisi.',
            'transaction_date.date'          => 'Format tanggal transaksi tidak valid.',
            'transaction_date.before_or_equal' => 'Tanggal transaksi tidak boleh berada di masa depan.',
        ];
    }
}
