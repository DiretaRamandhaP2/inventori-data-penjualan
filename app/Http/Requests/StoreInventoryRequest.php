<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryRequest extends FormRequest
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
            'name'     => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'price'    => ['required', 'numeric', 'min:0'],
            'stock'    => ['required', 'integer', 'min:0'],
        ];
    }

    /**
     * Custom message for validation errors in Indonesian.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'     => 'Nama produk wajib diisi.',
            'name.max'          => 'Nama produk maksimal 255 karakter.',
            'category.required' => 'Kategori wajib dipilih atau diisi.',
            'price.required'    => 'Harga produk wajib diisi.',
            'price.numeric'     => 'Harga harus berupa angka.',
            'price.min'         => 'Harga tidak boleh kurang dari 0.',
            'stock.required'    => 'Stok wajib diisi.',
            'stock.integer'     => 'Stok harus berupa bilangan bulat.',
            'stock.min'         => 'Stok tidak boleh kurang dari 0.',
        ];
    }
}
