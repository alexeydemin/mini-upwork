<?php

namespace App\Models;

use App\Exceptions\NotEnoughCoinsException;
use Illuminate\Support\Facades\Auth;

class Coin
{

    public function creditCoins($user)
    {
        //Auth::user()->getAuthPassword();
    }

    public static function chargeForVacancy()
    {
        $coinsAmount = Auth::user()->coins;
        $newAmount = $coinsAmount - env('VACANCY_PRICE');
        if ($newAmount >= 0) {
            Auth::user()->update([
                'coins' => $newAmount
            ]);
        } else {
            throw new NotEnoughCoinsException();
        }
    }

    public function chargeForResponse()
    {

    }
}
