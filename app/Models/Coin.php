<?php

namespace App\Models;

use App\Exceptions\NotEnoughCoinsException;
use Illuminate\Support\Facades\Auth;

class Coin
{
    public static function creditCoins($user)
    {
        $coinsAmount = $user->coins;
        $newAmount = $coinsAmount + config('app.credit_coins_per_day');
        if ($newAmount <= config('app.max_coins_per_user')) {
            $user->update([
                'coins' => $newAmount
            ]);
        }
    }

    public static function chargeForVacancy()
    {
        self::chargeForAction(config('app.vacancy_price'));
    }

    public static function chargeForResponse()
    {
        self::chargeForAction(config('app.response_price'));
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
