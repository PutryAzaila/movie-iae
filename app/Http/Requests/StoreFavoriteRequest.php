<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Form Request: StoreFavoriteRequest
 *
 * Menangani validasi data sebelum menyimpan film favorit ke database.
 * Dengan menggunakan Form Request, logika validasi dipisahkan dari controller
 * sehingga controller lebih bersih dan fokus.
 */
class StoreFavoriteRequest extends FormRequest
{
    /**
     * Tentukan apakah user boleh membuat request ini.
     * Kembalikan true untuk mengizinkan semua user (tidak ada auth).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Definisikan aturan validasi untuk setiap field.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // tmdb_id wajib dan angka
            // Validasi unik per-client dilakukan di controller API
            'tmdb_id'      => 'required|integer',

            // Judul film wajib ada, maksimal 255 karakter
            'title'        => 'required|string|max:255',

            // Sinopsis tidak wajib, tipe string
            'overview'     => 'nullable|string',

            // URL poster tidak wajib, harus format URL valid
            'poster_path'  => 'nullable|string|max:500',

            // Tanggal rilis tidak wajib, harus format tanggal yang valid
            'release_date' => 'nullable|date',

            // Rating tidak wajib, antara 0.0 hingga 10.0
            'vote_average' => 'nullable|numeric|min:0|max:10',
        ];
    }

    /**
     * Pesan error validasi dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tmdb_id.required'     => 'ID film dari TMDB wajib diisi.',
            'tmdb_id.integer'      => 'ID film harus berupa angka.',
            'title.required'       => 'Judul film wajib diisi.',
            'title.max'            => 'Judul film maksimal 255 karakter.',
            'release_date.date'    => 'Format tanggal rilis tidak valid (gunakan YYYY-MM-DD).',
            'vote_average.numeric' => 'Rating harus berupa angka.',
            'vote_average.min'     => 'Rating minimal 0.',
            'vote_average.max'     => 'Rating maksimal 10.',
        ];
    }

    /**
     * Override method ini agar validasi gagal mengembalikan
     * response JSON yang konsisten (bukan redirect HTML).
     * Ini penting karena kita membangun API, bukan web app biasa.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid.',
                'errors'  => $validator->errors(),
            ], 422) // 422 Unprocessable Entity
        );
    }
}
