<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model: Favorite
 *
 * Merepresentasikan film favorit yang disimpan di database lokal.
 * Menggunakan Eloquent ORM untuk berinteraksi dengan tabel `favorites`.
 *
 * @property int         $id
 * @property string      $session_id     Identitas pengunjung/session pemilik favorit
 * @property int         $tmdb_id        ID film dari TMDB
 * @property string      $title          Judul film
 * @property string|null $overview       Sinopsis film
 * @property string|null $poster_path    URL poster film
 * @property string|null $release_date   Tanggal rilis
 * @property float|null  $vote_average   Rating (0-10)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Favorite extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     * (Sebenarnya Laravel sudah otomatis pluralize, tapi ditulis eksplisit agar jelas)
     */
    protected $table = 'favorites';

    /**
     * Kolom-kolom yang boleh diisi secara massal (mass assignment).
     * Ini penting untuk keamanan — hanya kolom ini yang bisa di-fill() atau create().
     */
    protected $fillable = [
        'session_id',
        'tmdb_id',
        'title',
        'overview',
        'poster_path',
        'release_date',
        'vote_average',
    ];

    /**
     * Cast tipe data kolom secara otomatis.
     * Laravel akan otomatis konversi tipe saat membaca dari database.
     */
    protected $casts = [
        'session_id'   => 'string',
        'tmdb_id'      => 'integer',
        'vote_average' => 'float',
        'release_date' => 'date:Y-m-d',
    ];
}
