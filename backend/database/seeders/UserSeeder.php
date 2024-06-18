<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear un usuario administrador
        User::create([
            'name' => 'Admin',
            "last_name" => 'Admin Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password')
        ]);
        // Puedes agregar más usuarios aquí si es necesario
    }
}
