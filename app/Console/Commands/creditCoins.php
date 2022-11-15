<?php

namespace App\Console\Commands;

use App\Models\Coin;
use App\Models\User;
use Illuminate\Console\Command;

class creditCoins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coin:credit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily coins credit';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (User::all() as $user) {
            Coin::creditCoins($user);
        }

        return Command::SUCCESS;
    }
}
