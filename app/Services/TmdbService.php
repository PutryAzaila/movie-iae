<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service: TmdbService
 *
 * Bertanggung jawab untuk semua komunikasi dengan TMDB API.
 * Dengan memisahkan logic ini ke service class, controller
 * menjadi lebih ringan dan kode lebih mudah di-maintain.
 *
 * Dokumentasi API: https://developer.themoviedb.org/docs
 */
class TmdbService
{
    /**
     * Base URL TMDB API (dari .env)
     */
    private string $baseUrl;

    /**
     * API Key untuk autentikasi (dari .env)
     */
    private string $apiKey;

    /**
     * Base URL untuk gambar poster (dari .env)
     */
    private string $imageBaseUrl;

    /**
     * Constructor — inisialisasi konfigurasi dari .env
     */
    public function __construct()
    {
        $this->baseUrl     = config('services.tmdb.base_url', 'https://api.themoviedb.org/3');
        $this->apiKey      = config('services.tmdb.api_key', '');
        $this->imageBaseUrl = config('services.tmdb.image_base_url', 'https://image.tmdb.org/t/p/w500');
    }

    // =========================================================================
    // PUBLIC METHODS — Dipanggil oleh Controller
    // =========================================================================

    /**
     * Ambil daftar film terbaru (discover)
     *
     * @param  int $page Nomor halaman (default: 1)
     * @return array
     */
    public function getMovies(int $page = 1): array
    {
        $response = $this->get('/discover/movie', [
            'sort_by'  => 'popularity.desc',
            'page'     => $page,
            'language' => 'id',
        ]);

        return $this->formatMovieList($response);
    }

    /**
     * Ambil daftar film populer
     *
     * @param  int $page Nomor halaman (default: 1)
     * @return array
     */
    public function getPopularMovies(int $page = 1): array
    {
        $response = $this->get('/movie/popular', [
            'page'     => $page,
            'language' => 'id',
        ]);

        return $this->formatMovieList($response);
    }

    /**
     * Ambil daftar film trending hari ini
     *
     * @return array
     */
    public function getTrendingMovies(): array
    {
        // time_window: 'day' atau 'week'
        $response = $this->get('/trending/movie/day', [
            'language' => 'id',
        ]);

        return $this->formatMovieList($response);
    }

    /**
     * Cari film berdasarkan kata kunci judul
     *
     * @param  string $query Kata kunci pencarian
     * @param  int    $page  Nomor halaman (default: 1)
     * @return array
     */
    public function searchMovies(string $query, int $page = 1): array
    {
        $response = $this->get('/search/movie', [
            'query'    => $query,
            'page'     => $page,
            'language' => 'id',
        ]);

        return $this->formatMovieList($response);
    }

    /**
     * Ambil detail lengkap satu film berdasarkan TMDB ID
     *
     * @param  int $movieId TMDB Movie ID
     * @return array
     */
    public function getMovieDetail(int $movieId): array
    {
        // Get film detail dengan bahasa Indonesia
        $response = $this->get("/movie/{$movieId}", [
            'language'           => 'id',
            'append_to_response' => 'credits,release_dates', // Detail film & cast
        ]);

        // Get videos TANPA language parameter (TMDB hanya punya trailers dalam English)
        $videoResponse = $this->get("/movie/{$movieId}/videos", []);
        if (!empty($videoResponse['results'])) {
            $response['videos'] = ['results' => $videoResponse['results']];
        }

        return $this->formatMovieDetail($response);
    }

    // =========================================================================
    // PRIVATE METHODS — Hanya digunakan di dalam class ini
    // =========================================================================

    /**
     * Method utama untuk melakukan HTTP GET request ke TMDB API
     *
     * @param  string $endpoint Path endpoint (contoh: '/movie/popular')
     * @param  array  $params   Query parameters tambahan
     * @return array  Data respons dari API
     *
     * @throws \Exception Jika request gagal
     */
    private function get(string $endpoint, array $params = []): array
    {
        // Selalu sertakan API key di setiap request
        $params['api_key'] = $this->apiKey;

        try {
            $response = Http::timeout(10) // Timeout 10 detik
                ->get($this->baseUrl . $endpoint, $params);

            // Cek apakah response sukses (HTTP 200)
            if ($response->successful()) {
                return $response->json();
            }

            // Log error jika response tidak sukses
            Log::error('TMDB API Error', [
                'endpoint' => $endpoint,
                'status'   => $response->status(),
                'body'     => $response->body(),
            ]);

            throw new \Exception(
                "TMDB API mengembalikan status {$response->status()}: {$response->body()}"
            );

        } catch (ConnectionException $e) {
            Log::error('TMDB Connection Error', ['message' => $e->getMessage()]);
            throw new \Exception('Tidak dapat terhubung ke TMDB API. Periksa koneksi internet.');
        }
    }

    /**
     * Format data list film dari respons mentah TMDB
     * agar konsisten dan mudah digunakan frontend
     *
     * @param  array $response Respons mentah dari TMDB
     * @return array Data terformat
     */
    private function formatMovieList(array $response): array
    {
        $movies = array_map(function ($movie) {
            return $this->formatBasicMovie($movie);
        }, $response['results'] ?? []);

        return [
            'movies' => $movies,
            'meta'   => [
                'page'          => $response['page'] ?? 1,
                'total_results' => $response['total_results'] ?? 0,
                'total_pages'   => $response['total_pages'] ?? 0,
            ],
        ];
    }

    /**
     * Format data satu film (versi ringkas untuk list)
     *
     * @param  array $movie Data film mentah dari TMDB
     * @return array Data film terformat
     */
    private function formatBasicMovie(array $movie): array
    {
        return [
            'id'           => $movie['id'],
            'title'        => $movie['title'] ?? $movie['original_title'] ?? 'Unknown',
            'overview'     => $movie['overview'] ?? null,
            'poster_path'  => $this->buildImageUrl($movie['poster_path'] ?? null),
            'release_date' => $movie['release_date'] ?? null,
            'vote_average' => round($movie['vote_average'] ?? 0, 1),
            'popularity'   => round($movie['popularity'] ?? 0, 2),
        ];
    }

    /**
     * Format data detail film (lebih lengkap dari list)
     *
     * @param  array $movie Data film mentah dari TMDB
     * @return array Data film detail terformat
     */
    private function formatMovieDetail(array $movie): array
    {
        // Ambil data cast (5 aktor pertama)
        $cast = [];
        if (isset($movie['credits']['cast'])) {
            $cast = array_slice(array_map(function ($actor) {
                return [
                    'name'       => $actor['name'],
                    'character'  => $actor['character'],
                    'profile'    => $this->buildImageUrl($actor['profile_path'] ?? null),
                ];
            }, $movie['credits']['cast']), 0, 5);
        }

        // Ambil trailer YouTube (jika ada)
        // Loop: cari 'Trailer' dulu, jika tidak ada cari 'Teaser', terakhir ambil video YouTube pertama
        $trailer = null;
        if (isset($movie['videos']['results']) && is_array($movie['videos']['results'])) {
            // Priority 1: Cari video dengan type 'Trailer'
            foreach ($movie['videos']['results'] as $video) {
                if (($video['site'] ?? null) === 'YouTube' && ($video['type'] ?? null) === 'Trailer') {
                    $trailer = "https://www.youtube.com/watch?v={$video['key']}";
                    break;
                }
            }
            
            // Priority 2: Jika tidak ada trailer, cari 'Teaser'
            if (!$trailer) {
                foreach ($movie['videos']['results'] as $video) {
                    if (($video['site'] ?? null) === 'YouTube' && ($video['type'] ?? null) === 'Teaser') {
                        $trailer = "https://www.youtube.com/watch?v={$video['key']}";
                        break;
                    }
                }
            }
            
            // Priority 3: Jika masih tidak ada, ambil video YouTube pertama apapun
            if (!$trailer) {
                foreach ($movie['videos']['results'] as $video) {
                    if (($video['site'] ?? null) === 'YouTube') {
                        $trailer = "https://www.youtube.com/watch?v={$video['key']}";
                        break;
                    }
                }
            }
        }

        // Format genre
        $genres = array_map(fn($g) => $g['name'], $movie['genres'] ?? []);

        return [
            'id'            => $movie['id'],
            'title'         => $movie['title'] ?? 'Unknown',
            'tagline'       => $movie['tagline'] ?? null,
            'overview'      => $movie['overview'] ?? null,
            'poster_path'   => $this->buildImageUrl($movie['poster_path'] ?? null),
            'backdrop_path' => $this->buildImageUrl($movie['backdrop_path'] ?? null, 'original'),
            'release_date'  => $movie['release_date'] ?? null,
            'runtime'       => $movie['runtime'] ?? null,
            'vote_average'  => round($movie['vote_average'] ?? 0, 1),
            'vote_count'    => $movie['vote_count'] ?? 0,
            'popularity'    => round($movie['popularity'] ?? 0, 2),
            'genres'        => $genres,
            'cast'          => $cast,
            'trailer_url'   => $trailer,
        ];
    }

    /**
     * Bangun URL lengkap gambar dari path yang diberikan TMDB
     *
     * @param  string|null $path  Path gambar dari TMDB (contoh: "/abc123.jpg")
     * @param  string      $size  Ukuran gambar ('w500' atau 'original')
     * @return string|null URL lengkap gambar, atau null jika tidak ada
     */
    private function buildImageUrl(?string $path, string $size = 'w500'): ?string
    {
        if (empty($path)) {
            return null;
        }

        // Jika size bukan default, ganti base URL-nya
        if ($size !== 'w500') {
            $baseUrl = str_replace('w500', $size, $this->imageBaseUrl);
            return $baseUrl . $path;
        }

        return $this->imageBaseUrl . $path;
    }
}
