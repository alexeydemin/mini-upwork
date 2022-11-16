<?php

namespace Database\Factories;

use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vacancy>
 */
class VacancyFactory extends Factory
{
    protected $model = Vacancy::class;

    public function definition()
    {
        return [
            'title' => fake()->title(),
            'description' => fake()->text(),
        ];
    }
}
