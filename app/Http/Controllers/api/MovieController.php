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

    /**
     * @param  Request 
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->query('page', 1);

        $data = $this->tmdbService->getMovies($page);

        return $this->successResponse(
            data: $data['movies'],
            message: 'Daftar film berhasil diambil',
            meta: $data['meta']
        );
    }

    /**
     * @param  Request
     * @return JsonResponse
     */
    public function popular(Request $request): JsonResponse
    {
        $page = (int) $request->query('page', 1);

        $data = $this->tmdbService->getPopularMovies($page);

        return $this->successResponse(
            data: $data['movies'],
            message: 'Film populer berhasil diambil',
            meta: $data['meta']
        );
    }

    /**
     * @return JsonResponse
     */
    public function trending(): JsonResponse
    {
        $data = $this->tmdbService->getTrendingMovies();

        return $this->successResponse(
            data: $data['movies'],
            message: 'Film trending berhasil diambil',
            meta: $data['meta']
        );
    }

    /**
     * @param  Request 
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q'    => 'required|string|min:1|max:100',
            'page' => 'nullable|integer|min:1',
        ]);

        $query = $request->query('q');
        $page  = (int) $request->query('page', 1);

        $data = $this->tmdbService->searchMovies($query, $page);

        return $this->successResponse(
            data: $data['movies'],
            message: "Hasil pencarian untuk: \"{$query}\"",
            meta: $data['meta']
        );
    }

    /**
     * @param  int 
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $movie = $this->tmdbService->getMovieDetail($id);

        return $this->successResponse(
            data: $movie,
            message: 'Detail film berhasil diambil'
        );
    }

    /**
     * @param  mixed       
     * @param  string      
     * @param  array|null 
     * @return JsonResponse
     */
    private function successResponse(
        mixed $data,
        string $message = 'Berhasil',
        ?array $meta = null,
        int $code = 200
    ): JsonResponse {
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
