<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFavoriteRequest;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Controller: FavoriteController
 *
 * Menangani semua operasi CRUD untuk film favorit
 * yang disimpan di DATABASE LOKAL (MySQL).
 *
 * Catatan: Controller ini TIDAK berkomunikasi dengan TMDB API.
 * Data film sudah dikirimkan dari client (setelah diambil dari TMDB),
 * lalu disimpan ke database lokal kita.
 *
 * Operasi yang tersedia:
 * - index()   : Tampilkan semua favorit
 * - store()   : Simpan film ke favorit
 * - destroy() : Hapus film dari favorit
 */
class FavoriteController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $clientKey = $this->resolveClientKey($request);
        $favorites = Favorite::where('session_id', $clientKey)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar film favorit berhasil diambil',
            'data'    => $favorites,
            'meta'    => [
                'total' => $favorites->count(),
            ],
        ]);
    }

    /**
     * @param  StoreFavoriteRequest 
     * @return JsonResponse
     */
    public function store(StoreFavoriteRequest $request): JsonResponse
    {
        $clientKey = $this->resolveClientKey($request);

        $payload = $request->validated();
        $payload['session_id'] = $clientKey;

        $favorite = Favorite::firstOrCreate(
            [
                'session_id' => $clientKey,
                'tmdb_id' => $payload['tmdb_id'],
            ],
            $payload
        );

        if (! $favorite->wasRecentlyCreated) {
            return response()->json([
                'success' => false,
                'message' => 'Film ini sudah ada di daftar favorit.',
                'data' => $favorite,
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => "Film \"{$favorite->title}\" berhasil ditambahkan ke favorit",
            'data'    => $favorite,
        ], 201); 
    }

    /**
     * @param  int 
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $clientKey = $this->resolveClientKey($request);
        $favorite = Favorite::where('session_id', $clientKey)->findOrFail($id);

        $title = $favorite->title;
        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => "Film \"{$title}\" berhasil dihapus dari favorit",
            'data'    => null,
        ]);
    }

    private function resolveClientKey(Request $request): string
    {
        $providedKey = trim((string) $request->header('X-Client-Key', ''));

        if ($providedKey !== '') {
            return Str::limit($providedKey, 100, '');
        }

        return substr(hash('sha256', ($request->ip() ?? 'unknown') . '|' . ($request->userAgent() ?? '')), 0, 64);
    }
}
