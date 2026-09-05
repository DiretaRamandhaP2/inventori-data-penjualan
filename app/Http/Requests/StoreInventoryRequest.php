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
     * Prepare the data for validation.
     * Trim input nama produk sebelum divalidasi agar input yang hanya berisi spasi menjadi string kosong.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => is_string($this->name) ? trim($this->name) : $this->name,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // name: wajib diisi, minimal 3 karakter (menghindari input asal seperti "a"), maks 255
            'name'     => ['required', 'string', 'min:3', 'max:255'],

            // category: menggunakan rule `in:Fashion,Aksesoris,Lifestyle` karena sistem menggunakan
            // dropdown tetap dengan 3 opsi tersebut untuk menjaga konsistensi data di database.
            'category' => ['required', 'string', 'in:Fashion,Aksesoris,Lifestyle'],

            // price: `min:1` digunakan karena harga 0 tidak masuk akal untuk produk yang dijual.
            // regex `/^\d+(\.\d{1,2})?$/` memastikan hanya berupa angka dengan maksimal 2 angka desimal tanpa simbol/huruf.
            'price'    => ['required', 'numeric', 'min:1', 'regex:/^\d+(\.\d{1,2})?$/'],

            // stock: `min:0` karena stok 0 adalah kondisi valid (produk habis),
            // rule `integer` memastikan stok harus bilangan bulat (menolak desimal seperti 5.5).
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
            'name.string'       => 'Nama produk harus berupa teks.',
            'name.min'          => 'Nama produk minimal 3 karakter.',
            'name.max'          => 'Nama produk maksimal 255 karakter.',
            'category.required' => 'Kategori wajib dipilih.',
            'category.in'       => 'Kategori yang dipilih tidak valid.',
            'price.required'    => 'Harga wajib diisi.',
            'price.numeric'     => 'Harga wajib berupa angka.',
            'price.min'         => 'Harga tidak boleh 0 atau negatif.',
            'price.regex'       => 'Format harga tidak valid (maksimal 2 angka desimal).',
            'stock.required'    => 'Stok wajib diisi.',
            'stock.integer'     => 'Stok harus berupa bilangan bulat, tidak boleh desimal.',
            'stock.min'         => 'Stok tidak boleh negatif.',
        ];
    }
}
