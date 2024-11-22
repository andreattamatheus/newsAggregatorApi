<?php

namespace Tests\Feature;

use App\Models\Preference;
use Tests\TestCase;
use App\Models\User;

class ApiRoutesTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => fake()->email,
            'password' => bcrypt('password'),
        ]);
    }

    public function test_user_can_register()
    {
        $fakeEmail = fake()->email;
        $response = $this->postJson('api/v1/register', [
            'name' => 'Test User',
            'email' => $fakeEmail,
            'password' => 'password',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => $fakeEmail]);
    }

    public function test_user_can_login()
    {
        $response = $this->postJson('api/v1/login', [
            'email' => 'backoffice@yopmail.com',
            'password' => '123123123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token']);
    }

    public function test_user_can_request_password_reset()
    {
        $response = $this->postJson('api/v1/user/forgot-password', [
            'email' => $this->user->email,
        ]);

        $response->assertStatus(302);
    }

    public function test_user_can_reset_password()
    {
        $token = 'dummy-token';

        $response = $this->postJson('api/v1/user/reset-password', [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_access_preferences()
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->getJson('api/v1/user/preferences');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_update_preferences()
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->postJson('api/v1/user/preferences', [
            'type' => Preference::first()->type,
            'values' => 'dark',
        ]);

        $response->assertStatus(201);
    }

    public function test_authenticated_user_can_access_news_feed()
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->getJson('api/v1/user/news-feed');

        $response->assertStatus(200);
    }

    public function test_articles_api_resource_endpoints()
    {
        $this->actingAs($this->user, 'sanctum');

        // Test index
        $response = $this->getJson('api/v1/articles');
        $response->assertStatus(200);


        $articleId = $response->json('id');

        // Test show
        $response = $this->getJson("api/v1/articles/{$articleId}");
        $response->assertStatus(200);
    }
}
