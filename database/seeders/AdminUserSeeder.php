<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // updateOrCreate permet de relancer le seeder sans creer de doublons
        User::updateOrCreate(
            ['email' => 'admin@sales.com'],
            [
                'name' => 'Administrateur sales',
                'password' => Hash::make('password'), // Le mot de passe sera "password"
            ]
        );
    }
}