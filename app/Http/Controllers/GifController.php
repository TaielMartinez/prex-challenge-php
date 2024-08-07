<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\GhipyTrait;
use App\Traits\JsonResponseTrait;

class GifController extends Controller
{
    use GhipyTrait;
    use JsonResponseTrait;

    public function getById($ghipyId)
    {
        return $this->responseJson(...$this->ghipyById($ghipyId));
    }

    public function paginate(Request $request)
    {
        $query = request('query', request('QUERY'));
        $limit = request('limit', request('LIMIT'));
        $offset = request('offset', request('OFFSET'));

        return $this->responseJson(...$this->ghipyPaginate($query, $limit, $offset));
    }

    public function getFavorites()
    {
        $user_id = Auth::id();
        $favorites = Favorite::where('user_id', $user_id)->get();

        $favoritesGif = $favorites->map(function ($favorite) {
            return array_merge($favorite->toArray(), $this->ghipyById($favorite->ghipy_id, 43200));
        })->toArray();

        return $this->responseJson($favoritesGif);
    }

    public function addToFavorite(Request $request)
    {
        $user_id = Auth::id();
        $ghipy_id = $request->input('gif_id', $request->input('GIF_ID'));
        $alias = $request->input('alias', $request->input('ALIAS'));
        $request_user_id = $request->input('user_id', $request->input('USER_ID'));

        if ($request_user_id !== $user_id) {
            return response()->json(['error' => 'El usuario no coincide'], 401);
        }

        $favorite = Favorite::where('user_id', $user_id)->where('ghipy_id', $ghipy_id)->first();

        if (!$favorite) {
            Favorite::create([
                'user_id' => $user_id,
                'ghipy_id' => $ghipy_id,
                'alias' => $alias,
            ]);
            return $this->responseJson('Favorite added successfully');
        } else {
            return $this->responseJson('', 'Favorite already exists', 409);
        }
    }
}
