<?php
namespace App\Http\Controllers;
use App\Models\Favorite;
use App\Services\TmdbService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MoviePageController extends Controller {
    public function __construct(private TmdbService $tmdb) {}

    public function index(Request $request): View {
        $query  = $request->get('q', '');
        $page   = (int) $request->get('page', 1);
        $result = $query ? $this->tmdb->searchMovies($query, $page) : $this->tmdb->getPopularMovies($page);
        return view('movies.index', ['movies' => $result['movies'], 'meta' => $result['meta'], 'query' => $query, 'page' => $page]);
    }

    public function show(Request $request, int $id): View {
        $movie = $this->tmdb->getMovieDetail($id);

        $sessionId = $request->session()->getId();
        $favRecord = Favorite::where('session_id', $sessionId)
            ->where('tmdb_id', $id)
            ->first();

        $isFav = $favRecord !== null;

        return view('movies.show', compact('movie', 'isFav', 'favRecord'));
    }
}
