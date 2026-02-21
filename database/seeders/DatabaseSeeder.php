<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criação apenas do usuário admin obrigatório
        User::updateOrCreate(
            ['email' => 'adimin'], // Utilizando o campo email como login conforme solicitado
            [
                'name' => 'Médico Administrador',
                'password' => Hash::make('natallya'),
            ]
        );
    }
}
