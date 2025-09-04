<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nombre' => 'Juan Perez',
            'telefono' => '60006000',
            'documento' => '00000000-1',
            'direccion' => 'San Salvador',
            'email' => 'juan.perez.2025@test',
            'usuario' => 'admin',
            'password' => Hash::make('admin25'),
            'pass_show' => encrypt('admin25'),
            'estado' => 'Activo',
            'categoria' => 'SuperAdmin',
            'cargo' => 'Usuario administrador',
        ]);
    }
}
