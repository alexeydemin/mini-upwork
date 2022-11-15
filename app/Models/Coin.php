<?php

namespace App\Models;

use App\Exceptions\NotEnoughCoinsException;
use Illuminate\Support\Facades\Auth;

class Coin
{

    public static function creditCoins($user)
    {
        $coinsAmount = $user->coins;
        $newAmount = $coinsAmount + env('CREDIT_COINS_PER_DAY');
        if ($newAmount <= env('MAX_COINS_PER_USER')) {
            $user->update([
                'coins' => $newAmount
            ]);
        }
    }

    public static function chargeForVacancy()
    {
        self::chargeForAction(env('VACANCY_PRICE'));
    }

    public static function chargeForResponse()
    {
        self::chargeForAction(env('RESPONSE_PRICE'));
    }

    private static function chargeForAction($chargeAmount)
    {
        $coinsAmount = Auth::user()->coins;
        $newAmount = $coinsAmount - $chargeAmount;
        if ($newAmount >= 0) {
            Auth::user()->update([
                'coins' => $newAmount
            ]);
        } else {
            throw new NotEnoughCoinsException();
        }
    }
}
