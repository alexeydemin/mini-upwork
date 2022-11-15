<?php

namespace App\Observers;

use App\Exceptions\RateLimitException;
use App\Models\Vacancy;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class VacancyObserver
{
    /**
     * Handle the Vacancy "creating" event.
     *
     * @param  \App\Models\Vacancy $vacancy
     * @return void
     */
    public function creating()
    {
        $user = Auth::user();
        if (!RateLimiter::remaining('post-vacancy:' . $user->id, env('MAX_VACANCIES_PER_DAY'))) {
            $seconds = RateLimiter::availableIn('post-vacancy:' . $user->id);
            $waitingTime = CarbonInterval::seconds($seconds)->cascade();
            throw new RateLimitException($waitingTime);
        }

    }

    /**
     * Handle the Vacancy "created" event.
     *
     * @param  \App\Models\Vacancy  $vacancy
     * @return void
     */
    public function created(Vacancy $vacancy)
    {
        RateLimiter::hit('post-vacancy:' . Auth::user()->id, 24 * 60 * 60);
    }

    /**
     * Handle the Vacancy "updated" event.
     *
     * @param  \App\Models\Vacancy  $vacancy
     * @return void
     */
    public function updated(Vacancy $vacancy)
    {
        //
    }

    /**
     * Handle the Vacancy "deleted" event.
     *
     * @param  \App\Models\Vacancy  $vacancy
     * @return void
     */
    public function deleted(Vacancy $vacancy)
    {
        //
    }

    /**
     * Handle the Vacancy "restored" event.
     *
     * @param  \App\Models\Vacancy  $vacancy
     * @return void
     */
    public function restored(Vacancy $vacancy)
    {
        //
    }

    /**
     * Handle the Vacancy "force deleted" event.
     *
     * @param  \App\Models\Vacancy  $vacancy
     * @return void
     */
    public function forceDeleted(Vacancy $vacancy)
    {
        //
    }
}
