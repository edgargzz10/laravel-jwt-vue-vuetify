<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_valid_credentials()
    {
        // Create a user to perform the login test
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Make a POST request to the login endpoint
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // Verify that the response has a 200 OK status
        $response->assertStatus(200);

        // Verify that the response contains a token and user details
        $response->assertJsonStructure([
            'token',
            'user' => [
                'id',
                'name',
                'email',
            ],
        ]);
    }

    public function test_login_with_invalid_credentials()
    {
        // Create a user to perform the login test
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Make a POST request to the login endpoint with incorrect credentials
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // Verify that the response has a 401 Unauthorized status
        $response->assertStatus(401);

        // Verify that the response contains the expected error message
        $response->assertJson([
            'error' => 'Email and Password dont match.',
        ]);
    }

    public function test_login_with_missing_fields()
    {
        // Make a POST request to the login endpoint with missing fields
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            // Password field is missing
        ]);

        // Verify that the response has a 422 Unprocessable Entity status
        $response->assertStatus(422);

        // Verify that the response contains the expected validation errors
        $response->assertJsonStructure([
            'errors' => [
                'password',
            ],
        ]);
    }
}
