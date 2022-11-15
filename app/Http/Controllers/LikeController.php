<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class LikeController extends Controller
{

    public function indexVacancies(Request $request)
    {
        $likes = Like::where('user_id', $request->user()->id)
            ->where('likable_type', Vacancy::class)->get();

        return response()->json($likes);
    }

    public function indexUsers(Request $request)
    {
        $likes = Like::where('user_id', $request->user()->id)
            ->where('likable_type', User::class)->get();

        return response()->json($likes);
    }

    public function storeVacancy(Request $request, Vacancy $vacancy)
    {
        $vacancy->likes()->save(new Like([
            'user_id' => $request->user()->id
        ]));

        return response()->json(['status' => 'OK']);
    }

    public function storeUser(Request $request, User $user)
    {
        $user->likes()->save(new Like([
            'user_id' => $request->user()->id
        ]));

        return response()->json(['status' => 'OK']);
    }


}
