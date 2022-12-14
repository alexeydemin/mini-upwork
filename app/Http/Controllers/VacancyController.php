<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyVacancyRequest;
use App\Http\Requests\IndexVacancyRequest;
use App\Http\Requests\StoreVacancyRequest;
use App\Http\Requests\UpdateVacancyRequest;
use App\Models\Coin;
use App\Models\Like;
use App\Models\Response;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\IndexVacancyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexVacancyRequest $request): JsonResponse
    {
        $list = Vacancy::getAll(
            creationDate: $request->date,
            creationWeek: $request->week,
            creationMonth: $request->month,
            sort: $request->sort,
            sortDir: $request->dir,
        );

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreVacancyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreVacancyRequest $request): JsonResponse
    {
        $vacancy = DB::transaction(function () use ($request) {
            Coin::chargeForVacancy();
            return Auth::user()->vacancies()->create([
                'title' => $request->title,
                'description' => $request->description,
            ]);
        });

        return response()->json($vacancy->withoutRelations());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Vacancy $vacancy
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Vacancy $vacancy): JsonResponse
    {
        return response()->json($vacancy->withoutRelations());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateVacancyRequest $request
     * @param \App\Models\Vacancy $vacancy
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateVacancyRequest $request, Vacancy $vacancy): JsonResponse
    {
        $vacancy->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json($vacancy->withoutRelations());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Http\Requests\DestroyVacancyRequest $request
     * @param \App\Models\Vacancy $vacancy
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyVacancyRequest $request, Vacancy $vacancy): JsonResponse
    {
        Like::destroy($vacancy->likes);
        Response::destroy($vacancy->responses);
        $vacancy->delete();

        return response()->json(['status' => 'OK']);
    }
}
