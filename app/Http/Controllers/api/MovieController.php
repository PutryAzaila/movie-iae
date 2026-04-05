<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TmdbService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function __construct(
        private TmdbService $tmdbService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->query('page', 1);
        $data = $this->tmdbService->getMovies($page);

        return $this->ok($data['movies'], 'Daftar film berhasil diambil', $data['meta']);
    }

    public function popular(Request $request): JsonResponse
    {
        $page = (int) $request->query('page', 1);
        $data = $this->tmdbService->getPopularMovies($page);

        return $this->ok($data['movies'], 'Film populer berhasil diambil', $data['meta']);
    }

    public function trending(): JsonResponse
    {
        $data = $this->tmdbService->getTrendingMovies();

        return $this->ok($data['movies'], 'Film trending berhasil diambil', $data['meta']);
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q'    => 'required|string|min:1|max:100',
            'page' => 'nullable|integer|min:1',
        ]);

        $query = $request->query('q');
        $page  = (int) $request->query('page', 1);
        $data  = $this->tmdbService->searchMovies($query, $page);

        return $this->ok($data['movies'], "Hasil pencarian untuk: \"{$query}\"", $data['meta']);
    }

    public function show(int $id): JsonResponse
    {
        $movie = $this->tmdbService->getMovieDetail($id);

        return $this->ok($movie, 'Detail film berhasil diambil');
    }

    private function ok(mixed $data, string $message = 'Berhasil', ?array $meta = null, int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];

        if ($meta !== null) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $code);
    }
}