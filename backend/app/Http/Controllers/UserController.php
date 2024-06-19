<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); // Apply 'auth:api' middleware to all methods in this controller
    }

    public function index()
    {
        // Retrieve all users
        $users = User::all();

        // Return JSON response with success status and users data
        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }

    public function show($id)
    {
        // Find user by ID
        $user = User::find($id);

        // Return JSON response with success status and user data
        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
        ]);

        // If validation fails, return errors with a status code of 422
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Find user by ID
            $user = User::findOrFail($id);

            // Update user data
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->save();

            // Return JSON response with success status, message, and updated user data
            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            // Handle other possible errors
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function store(Request $request)
    {
        // Validate incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Create a new user without returning the token
            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Return JSON response with success status, message, and created user data
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            // In case of error, return a generic server error message
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function destroy($id)
    {
        // Find user by ID
        $user = User::find($id);

        // Delete the user
        $user->delete();

        // Return JSON response with success status, message, and deleted user data
        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully',
            'user' => $user,
        ]);
    }

    public function changePasswordForUser(Request $request, $id)
    {
        // Find user by ID
        $user = User::find($id);

        // If user is not found, return a 404 response
        if (is_null($user)) {
            return response()->json(['message' => 'User Not Found'], 404);
        }

        // Validate incoming data for new password
        $validatedData = $request->validate([
            'new_password' => 'required|string|min:8',
        ]);

        // Update user's password with hashed new password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Return JSON response with success message
        return response()->json(['message' => 'Password Changed Successfully']);
    }
}
