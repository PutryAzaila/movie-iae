<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | File ini menyimpan konfigurasi untuk layanan pihak ketiga (third-party).
    | Tambahkan konfigurasi TMDB di sini agar bisa diakses via config('services.tmdb').
    |
    | Nilai diambil dari file .env agar aman dan tidak ter-commit ke Git.
    |
    */

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | TMDB (The Movie Database) API Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk mengakses TMDB API.
    | Nilai diambil dari .env dengan fallback ke nilai default.
    |
    | Cara mendapatkan API Key:
    | 1. Daftar di https://www.themoviedb.org/signup
    | 2. Login → Settings → API → Create (Developer)
    | 3. Copy "API Key (v3 auth)" ke .env
    |
    */

    'tmdb' => [
        // Base URL endpoint API
        'base_url'       => env('TMDB_BASE_URL', 'https://api.themoviedb.org/3'),

        // API Key untuk autentikasi (wajib diisi di .env)
        'api_key'        => env('TMDB_API_KEY', ''),

        // Base URL untuk gambar poster/backdrop
        'image_base_url' => env('TMDB_IMAGE_BASE_URL', 'https://image.tmdb.org/t/p/w500'),
    ],

];
