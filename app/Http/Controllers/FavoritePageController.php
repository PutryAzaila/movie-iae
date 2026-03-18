<?php
namespace App\Http\Controllers;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FavoritePageController extends Controller {
    public function index(Request $request): View {
        $sessionId = $request->session()->getId();

        $favorites = Favorite::where('session_id', $sessionId)
            ->orderBy('created_at','desc')
            ->get();

        return view('favorites.index', compact('favorites'));
    }

    public function store(Request $request) {
        $sessionId = $request->session()->getId();

        $v = $request->validate([
            'tmdb_id'      => [
                'required',
                'integer',
                Rule::unique('favorites', 'tmdb_id')->where(function ($query) use ($sessionId) {
                    return $query->where('session_id', $sessionId);
                }),
            ],
            'title'        => 'required|string|max:255',
            'overview'     => 'nullable|string',
            'poster_path'  => 'nullable|string',
            'release_date' => 'nullable|date',
            'vote_average' => 'nullable|numeric|min:0|max:10',
        ], [
            'tmdb_id.unique' => 'Film ini sudah ada di favorit kamu.',
        ]);

        $v['session_id'] = $sessionId;

        Favorite::create($v);
        return back()->with('success', "\"$v[title]\" ditambahkan ke favorit!");
    }

    public function destroy(Request $request, int $id) {
        $sessionId = $request->session()->getId();

        $f = Favorite::where('session_id', $sessionId)->findOrFail($id);
        $t = $f->title; $f->delete();
        return back()->with('success', "\"$t\" dihapus dari favorit.");
    }
}
