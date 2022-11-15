<?php

namespace App\Observers;

use App\Mail\ResponseReceived;
use App\Models\Response;
use Illuminate\Support\Facades\Mail;

class ResponseObserver
{
    /**
     * Handle the Response "created" event.
     *
     * @param \App\Models\Response $response
     * @return void
     */
    public function created(Response $response)
    {
        Mail::to($response->vacancy->user)->queue(new ResponseReceived($response));
    }
}
