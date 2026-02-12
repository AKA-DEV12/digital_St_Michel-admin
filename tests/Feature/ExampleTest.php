<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */

    use RefreshDatabase;
    public function test_the_application_returns_a_successful_response(): void
    {
        // On crée un utilisateur factice
        $user = User::factory()->create();

        // On simule la connexion et on appelle la page d'accueil
        $response = $this->actingAs($user)->get('/');

        // $response->assertStatus(200);
        $response->assertRedirect('/dashboard');
    }
}
