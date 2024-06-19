<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_as_authenticated_user()
    {
        // Create a user to authenticate
        $user = User::factory()->create();

        // Act as the created user
        $token = JWTAuth::fromUser($user);

        // Make a GET request to the index endpoint with the token
        $response = $this->getJson('/api/users', ['Authorization' => "Bearer $token"]);

        // Verify that the response has a 200 OK status
        $response->assertStatus(200);

        // Verify that the response contains the expected structure
        $response->assertJsonStructure([
            'status',
            'users' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                ],
            ],
        ]);

        // Verify that the response status is 'success'
        $response->assertJson(['status' => 'success']);
    }

    public function test_index_as_unauthenticated_user()
    {
        // Make a GET request to the index endpoint without a token
        $response = $this->getJson('/api/users');

        // Verify that the response has a 401 Unauthorized status
        $response->assertStatus(401);
    }

    public function test_show_as_authenticated_user()
    {
        // Create a user to authenticate
        $authenticatedUser = User::factory()->create();

        // Create another user to fetch details
        $userToShow = User::factory()->create();

        // Act as the authenticated user
        $token = JWTAuth::fromUser($authenticatedUser);

        // Make a GET request to the show endpoint with the token
        $response = $this->getJson("/api/users/{$userToShow->id}", ['Authorization' => "Bearer $token"]);

        // Verify that the response has a 200 OK status
        $response->assertStatus(200);

        // Verify that the response contains the expected structure
        $response->assertJsonStructure([
            'status',
            'user' => [
                'id',
                'name',
                'email',
            ],
        ]);

        // Verify that the response status is 'success'
        $response->assertJson(['status' => 'success']);
    }

    public function test_show_as_unauthenticated_user()
    {
        // Create a user to fetch details
        $userToShow = User::factory()->create();

        // Make a GET request to the show endpoint without a token
        $response = $this->getJson("/api/users/{$userToShow->id}");

        // Verify that the response has a 401 Unauthorized status
        $response->assertStatus(401);
    }

    public function test_update_as_authenticated_user()
    {
        // Create a user to authenticate
        $authenticatedUser = User::factory()->create();

        // Create another user to update
        $userToUpdate = User::factory()->create();

        // Act as the authenticated user
        $token = JWTAuth::fromUser($authenticatedUser);

        // New data for the user to be updated
        $newData = [
            'name' => 'New Name',
            'last_name' => 'New Last Name',
        ];

        // Make a PUT request to the update endpoint with the token
        $response = $this->putJson("/api/users/{$userToUpdate->id}", $newData, ['Authorization' => "Bearer $token"]);

        // Verify that the response has a 200 OK status
        $response->assertStatus(200);

        // Verify that the response contains the expected structure
        $response->assertJsonStructure([
            'status',
            'message',
            'user' => [
                'id',
                'name',
                'last_name',
                'email',
            ],
        ]);

        // Verify that the response status is 'success'
        $response->assertJson(['status' => 'success', 'message' => 'User updated successfully']);

        // Verify that the user's data has been updated
        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'name' => 'New Name',
            'last_name' => 'New Last Name',
        ]);
    }

    public function test_update_as_unauthenticated_user()
    {
        // Create a user to update
        $userToUpdate = User::factory()->create();

        // New data for the user to be updated
        $newData = [
            'name' => 'New Name',
            'last_name' => 'New Last Name',
        ];

        // Make a PUT request to the update endpoint without a token
        $response = $this->putJson("/api/users/{$userToUpdate->id}", $newData);

        // Verify that the response has a 401 Unauthorized status
        $response->assertStatus(401);
    }

    public function test_update_with_invalid_data()
    {
        // Create a user to authenticate
        $authenticatedUser = User::factory()->create();

        // Create another user to update
        $userToUpdate = User::factory()->create();

        // Act as the authenticated user
        $token = JWTAuth::fromUser($authenticatedUser);

        // Invalid data for the user to be updated
        $invalidData = [
            'name' => '', // Invalid: empty name
            'last_name' => 'N', // Invalid: too short
        ];

        // Make a PUT request to the update endpoint with the invalid data and token
        $response = $this->putJson("/api/users/{$userToUpdate->id}", $invalidData, ['Authorization' => "Bearer $token"]);

        // Verify that the response has a 422 Unprocessable Entity status
        $response->assertStatus(422);

        // Verify that the response contains the expected validation errors
        $response->assertJsonStructure([
            'errors' => [
                'name',
                'last_name',
            ],
        ]);
    }

    public function test_store_as_authenticated_user()
    {
        // Create a user to authenticate
        $authenticatedUser = User::factory()->create();

        // Act as the authenticated user
        $token = JWTAuth::fromUser($authenticatedUser);

        // Data for the new user to be created
        $userData = [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ];

        // Make a POST request to the store endpoint with the token
        $response = $this->postJson('/api/users', $userData, ['Authorization' => "Bearer $token"]);

        // Verify that the response has a 201 Created status
        $response->assertStatus(201);

        // Verify that the response contains the expected structure
        $response->assertJsonStructure([
            'status',
            'message',
            'user' => [
                'id',
                'name',
                'last_name',
                'email',
            ],
        ]);

        // Verify that the response status is 'success'
        $response->assertJson(['status' => 'success', 'message' => 'User created successfully']);

        // Verify that the user has been created in the database
        $this->assertDatabaseHas('users', [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);
    }

    public function test_store_as_unauthenticated_user()
    {
        // Data for the new user to be created
        $userData = [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ];

        // Make a POST request to the store endpoint without a token
        $response = $this->postJson('/api/users', $userData);

        // Verify that the response has a 401 Unauthorized status
        $response->assertStatus(401);

        // Verify that the user has not been created in the database
        $this->assertDatabaseMissing('users', [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);
    }

    public function test_store_with_invalid_data()
    {
        // Create a user to authenticate
        $authenticatedUser = User::factory()->create();

        // Act as the authenticated user
        $token = JWTAuth::fromUser($authenticatedUser);

        // Invalid data for creating a new user (missing required fields)
        $invalidData = [
            'name' => '', // Invalid: empty name
            'email' => 'invalid-email', // Invalid: invalid email format
            // 'password' is required but not provided
        ];

        // Make a POST request to the store endpoint with invalid data and token
        $response = $this->postJson('/api/users', $invalidData, ['Authorization' => "Bearer $token"]);

        // Verify that the response has a 422 Unprocessable Entity status
        $response->assertStatus(422);

        // Verify that the response contains the expected validation errors
        $response->assertJsonValidationErrors(['name', 'email']);

        // Verify that the user has not been created in the database
        $this->assertDatabaseMissing('users', [
            'name' => '',
            'email' => 'invalid-email'
                    ]);
    }

    public function test_destroy_as_authenticated_user()
    {
        // Create a user to authenticate
        $authenticatedUser = User::factory()->create();

        // Create another user to delete
        $userToDelete = User::factory()->create();

        // Authenticate as the user
        $token = JWTAuth::fromUser($authenticatedUser);

        // Make a DELETE request to the destroy endpoint with the token
        $response = $this->deleteJson("/api/users/{$userToDelete->id}", [], ['Authorization' => "Bearer $token"]);

        // Verify that the response has a 200 OK status
        $response->assertStatus(200);

        // Verify that the response contains the expected structure
        $response->assertJsonStructure([
            'status',
            'message',
            'user' => [
                'id',
                'name',
                'last_name',
                'email',
            ],
        ]);

        // Verify that the status in the JSON response is 'success' and the message is 'user deleted successfully'
        $response->assertJson(['status' => 'success', 'message' => 'User deleted successfully']);

        // Verify that the user has been deleted from the database
        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    public function test_destroy_as_unauthenticated_user()
    {
        // Create a user to delete
        $userToDelete = User::factory()->create();

        // Make a DELETE request to the destroy endpoint without an authorization token
        $response = $this->deleteJson("/api/users/{$userToDelete->id}");

        // Verify that the response has a 401 Unauthorized status
        $response->assertStatus(401);

        // Verify that the user has not been deleted from the database
        $this->assertDatabaseHas('users', ['id' => $userToDelete->id]);
    }

    public function test_change_password_for_authenticated_user()
    {
        // Create two users: the first one will be authenticated and the second one's password will be changed
        $authenticatedUser = User::factory()->create();
        $userToChangePassword = User::factory()->create();

        // Authenticate as the authenticated user and get the JWT token
        $token = auth()->login($authenticatedUser);

        // Valid data to change the password
        $newPasswordData = [
            'new_password' => 'newpassword123',
        ];

        // Make a POST request to the changePasswordForUser endpoint with the authorization token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->postJson("/api/users/{$userToChangePassword->id}/change-password", $newPasswordData);

        // Verify that the response has a 200 OK status
        $response->assertStatus(200);

        // Verify that the response contains the message 'Password Changed Successfully'
        $response->assertJson(['message' => 'Password Changed Successfully']);

        // Verify that the user's password has been updated in the database
        $this->assertTrue(Hash::check('newpassword123', $userToChangePassword->fresh()->password));
    }

    public function test_change_password_for_nonexistent_user()
    {
        // Create an authenticated user who will attempt to change the password of a nonexistent user
        $authenticatedUser = User::factory()->create();

        // Authenticate as the authenticated user
        $token = auth()->login($authenticatedUser);

        // Nonexistent user ID
        $nonexistentUserId = 9999;

        // Valid data to change the password (even though the user does not exist)
        $newPasswordData = [
            'new_password' => 'newpassword123',
        ];

        // Make a POST request to the changePasswordForUser endpoint with the authorization token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->postJson("/api/users/{$nonexistentUserId}/change-password", $newPasswordData);

        // Verify that the response has a 404 Not Found status
        $response->assertStatus(404);

        // Verify that no user with ID 9999 has been created
        $this->assertNull(User::find($nonexistentUserId));
    }

    public function test_change_password_with_invalid_data()
    {
        // Create two users: the first one will be authenticated and the second one's password will be changed
        $authenticatedUser = User::factory()->create();
        $userToChangePassword = User::factory()->create();

        // Authenticate as the authenticated user and get the JWT token
        $token = auth()->login($authenticatedUser);

        // Invalid data to change the password (less than 8 characters)
        $invalidPasswordData = [
            'new_password' => '123', // Less than 8 characters
        ];

        // Get the current password hash of the user before attempting to change it
        $currentPasswordHash = $userToChangePassword->password;

        // Make a POST request to the changePasswordForUser endpoint with the authorization token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->postJson("/api/users/{$userToChangePassword->id}/change-password", $invalidPasswordData);

        // Verify that the response has a 422 Unprocessable Entity status (due to failed validation)
        $response->assertStatus(422);

        // Verify that the user's password has not been changed in the database
        $this->assertEquals($currentPasswordHash, $userToChangePassword->fresh()->password);
    }
}
