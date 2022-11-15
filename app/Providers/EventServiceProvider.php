<?php

namespace App\Providers;

use App\Models\Response;
use App\Models\Vacancy;
use App\Observers\ResponseObserver;
use App\Observers\VacancyObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Vacancy::observe(VacancyObserver::class);
        Response::observe(ResponseObserver::class);
    }
}
