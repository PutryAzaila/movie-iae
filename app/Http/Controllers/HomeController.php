<?php
namespace App\Http\Controllers;
use App\Services\TmdbService;
use Illuminate\View\View;

class HomeController extends Controller {
    public function __construct(private TmdbService $tmdb) {}
    public function index(): View {
        $trending       = $this->tmdb->getTrendingMovies();
        $popular        = $this->tmdb->getPopularMovies();
        $featured       = $trending['movies'][0] ?? null;
        $trendingMovies = array_slice($trending['movies'], 1, 6);
        $popularMovies  = array_slice($popular['movies'], 0, 6);
        return view('home', compact('featured','trendingMovies','popularMovies'));
    }
}
