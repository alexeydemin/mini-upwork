<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyResponseRequest;
use App\Http\Requests\StoreResponseRequest;
use App\Models\Coin;
use App\Models\Response;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResponseController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreResponseRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreResponseRequest $request)
    {
        if (!Vacancy::find($request->vacancyId)) {
            throw new ModelNotFoundException();
        }

        $response = DB::transaction(function () use ($request) {
            Coin::chargeForResponse();
            return Auth::user()->responses()->create([
                'vacancy_id' => $request->vacancyId,
                'text' => $request->text,
            ]);
        });

        return response()->json($response->withoutRelations());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Http\Requests\DestroyResponseRequest $request
     * @param \App\Models\Response $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyResponseRequest $request, Response $response)
    {
        $response->delete();

        return response()->json(['status' => 'OK']);
    }
}
