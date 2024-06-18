<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class userController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $users = User::all();
        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    public function update(Request $request, $id)
{
    // Validar la solicitud
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|min:2|max:255',
        'last_name' => 'required|string|min:2|max:255',
    ]);

    // Si la validación falla, devolver los errores con un código de estado 422
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    try {
        // Encontrar el usuario por ID
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
    } catch (\Exception $e) {
        // Manejar otros posibles errores
        return response()->json(['error' => 'Server error'], 500);
    }
}

    public function store(Request $request)
    {
        // Validación de los datos de entrada
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|min:2|max:255',
        'last_name' => 'required|string|min:2|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    // Si la validación falla, devolvemos los errores
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    try {
        // Creación del nuevo usuario sin devolver el token
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    } catch (\Exception $e) {
        // En caso de error, devolvemos un mensaje genérico de error de servidor
        return response()->json(['error' => 'Server error'], 500);
    }
    }




    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'user deleted successfully',
            'user' => $user,
        ]);
    }

    public function changePasswordForUser(Request $request, $id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json(['message' => 'User Not Found'], 404);
        }

        $validatedData = $request->validate([
            'new_password' => 'required|string|min:8',
        ]);

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password Changed Successfully']);
    }


}
