<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_register()
    {
        $response = $this->post('api/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => '12345678'
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create();

        $response = $this->post('api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_see_profile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('api/profile');

        $response->assertStatus(200);
        $response->assertJson([
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('api/logout');

        $response->assertStatus(200);
    }
}
