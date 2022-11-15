<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyVacancyRequest;
use App\Http\Requests\IndexVacancyRequest;
use App\Http\Requests\StoreVacancyRequest;
use App\Http\Requests\UpdateVacancyRequest;
use App\Models\Like;
use App\Models\Response;
use App\Models\Vacancy;
use Carbon\CarbonInterval;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\RateLimiter;

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

        $user = $request->user();
        if (RateLimiter::remaining('post-vacancy:' . $user->id, env('MAX_VACANCIES_PER_DAY'))) {
            $vacancy = $user->vacancies()->create([
                'title' => $request->title,
                'description' => $request->description,
            ]);
            if ($vacancy) {
                RateLimiter::hit('post-vacancy:' . $user->id, 24*60*60);
                return response()->json($vacancy);
            }
        }
        $seconds = RateLimiter::availableIn('post-vacancy:'.$user->id);
        $human = CarbonInterval::seconds($seconds)->cascade();
        return response()->json([
            'status' => 'ERROR',
            'message' => "You can post new vacancy in $human"
        ], 429);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Vacancy $vacancy
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Vacancy $vacancy): JsonResponse
    {
        return response()->json($vacancy);
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
        $vacancy = $vacancy->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json($vacancy);
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
