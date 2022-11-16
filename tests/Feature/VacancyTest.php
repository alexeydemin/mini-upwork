<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class VacancyTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $user = User::factory()->create();
        Vacancy::unsetEventDispatcher();
        Vacancy::factory()->create(['user_id' => $user->id]);

        $response = $this->get('/api/vacancies');
        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->first(function ($json) {
                    $json
                        ->hasAll(['id', 'user_id', 'description', 'title'])
                        ->etc();
                });
            });
    }

    public function test_create_wo_bearer()
    {
        $response = $this->postJson('/api/vacancies', [
            'title' => 'New vacancy',
            'description' => 'Build a site'
        ]);
        $response
            ->assertStatus(401)
            ->assertJson([
                'status' => 'ERROR'
            ]);
    }


    public function test_create_wo_coins()
    {
        $user = User::factory()->create([
            'coins' => 1,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/vacancies', [
                'title' => 'New vacancy',
                'description' => 'Build a site'
            ]);
        $response
            ->assertStatus(400);
    }

    public function test_create_rate_limit()
    {
        $user = User::factory()->create([
            'coins' => 10,
        ]);

        $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/vacancies', [
                'title' => 'First',
                'description' => 'Build a site',
            ]);
        $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/vacancies', [
                'title' => 'Second',
                'description' => 'Build a site',
            ]);
        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/vacancies', [
                'title' => 'Third!',
                'description' => 'Build a site',
            ]);
        $response
            ->assertStatus(429);
    }

    public function test_create_no_title()
    {
        $user = User::factory()->create([
            'coins' => 5,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/vacancies', [
                'description' => 'Build a site',
            ]);
        $response
            ->assertStatus(422);
    }

    public function test_create()
    {
        $user = User::factory()->create([
            'coins' => 5,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/vacancies', [
                'title' => 'New vacancy',
                'description' => 'Build a site',
            ]);
        $response
            ->assertStatus(200)
            ->assertJson([
                'title' => 'New vacancy',
                'description' => 'Build a site',
            ]);
    }

    public function test_update_nonexistent()
    {
        Vacancy::unsetEventDispatcher();

        $user = User::factory()->create([
            'coins' => 5,
        ]);

        $vacancy = Vacancy::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->patchJson("/api/vacancies/789", [
                'title' => 'Updated title',
                'description' => 'Updated text',
            ]);

        $response
            ->assertStatus(404);
    }

    public function test_update_not_mine()
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
            ->patchJson("/api/vacancies/{$vacancy->id}", [
                'title' => 'Updated title',
                'description' => 'Updated text',
            ]);

        $response
            ->assertStatus(403);
    }

    public function test_update()
    {
        Vacancy::unsetEventDispatcher();

        $user = User::factory()->create([
            'coins' => 5,
        ]);

        $vacancy = Vacancy::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->patchJson("/api/vacancies/{$vacancy->id}", [
                'title' => 'Updated title',
                'description' => 'Updated text',
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'title' => 'Updated title',
                'description' => 'Updated text'
            ]);
    }

    public function test_delete()
    {
        Vacancy::unsetEventDispatcher();

        $user = User::factory()->create([
            'coins' => 5,
        ]);

        $vacancy = Vacancy::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->delete("/api/vacancies/{$vacancy->id}");

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'OK',
            ]);
    }

    public function test_show()
    {
        Vacancy::unsetEventDispatcher();

        $user = User::factory()->create([
            'coins' => 5,
        ]);

        $vacancy = Vacancy::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->get("/api/vacancies/{$vacancy->id}");

        $response
            ->assertStatus(200)
            ->assertJson([
                'title' => $vacancy->title,
                'description' => $vacancy->description,
            ]);
    }
}
