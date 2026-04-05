<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFavoriteRequest;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $key = $this->clientKey($request);

        $favorites = Favorite::where('session_id', $key)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar film favorit berhasil diambil',
            'data'    => $favorites,
            'meta'    => ['total' => $favorites->count()],
        ]);
    }

    public function store(StoreFavoriteRequest $request): JsonResponse
    {
        $key  = $this->clientKey($request);
        $data = array_merge($request->validated(), ['session_id' => $key]);

        $favorite = Favorite::firstOrCreate(
            ['session_id' => $key, 'tmdb_id' => $data['tmdb_id']],
            $data
        );

        if (! $favorite->wasRecentlyCreated) {
            return response()->json([
                'success' => false,
                'message' => 'Film ini sudah ada di daftar favorit.',
                'data'    => $favorite,
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => "Film \"{$favorite->title}\" berhasil ditambahkan ke favorit",
            'data'    => $favorite,
        ], 201);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $key      = $this->clientKey($request);
        $favorite = Favorite::where('session_id', $key)->findOrFail($id);
        $title    = $favorite->title;

        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => "Film \"{$title}\" berhasil dihapus dari favorit",
            'data'    => null,
        ]);
    }

    private function clientKey(Request $request): string
    {
        $header = trim((string) $request->header('X-Client-Key', ''));

        if ($header !== '') {
            return Str::limit($header, 100, '');
        }

        $raw = ($request->ip() ?? 'unknown') . '|' . ($request->userAgent() ?? '');

        return substr(hash('sha256', $raw), 0, 64);
    }
}