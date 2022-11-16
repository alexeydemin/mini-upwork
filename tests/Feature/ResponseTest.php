<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResponseTest extends TestCase
{
    use RefreshDatabase;

    public function test_response_own_vacancy()
    {
        Vacancy::unsetEventDispatcher();

        $user = User::factory()->create([
            'coins' => 5,
        ]);

        $vacancy = Vacancy::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson("/api/responses", [
                'vacancy_id' =>  $vacancy->id,
                'text' => 'I want to do this job!'
            ]);

        $response
            ->assertStatus(403);
    }

    public function test_response()
    {
        Vacancy::unsetEventDispatcher();

        $user = User::factory()->create([
            'coins' => 5,
        ]);

        $user2 = User::factory()->create([
            'coins' => 5,
        ]);

        $vacancy = Vacancy::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user2, 'sanctum')
            ->postJson("/api/responses", [
                'vacancy_id' =>  $vacancy->id,
                'text' => 'I want to do this job!'
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'vacancy_id' => $vacancy->id,
                'text' => 'I want to do this job!',
                'user_id' => $user2->id,
            ]);
    }

    public function test_delete_response()
    {
        Vacancy::unsetEventDispatcher();

        $user = User::factory()->create([
            'coins' => 5,
        ]);

        $user2 = User::factory()->create([
            'coins' => 5,
        ]);

        $vacancy = Vacancy::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user2, 'sanctum')
            ->postJson("/api/responses", [
                'vacancy_id' =>  $vacancy->id,
                'text' => 'I want to do this job!'
            ]);
        $responseId = $response->decodeResponseJson()['id'];

        $response = $this
            ->actingAs($user2, 'sanctum')
            ->delete("/api/responses/$responseId");

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'OK',
            ]);
    }
}
