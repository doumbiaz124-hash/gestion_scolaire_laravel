<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Utilisateur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['nom_role' => 'Admin']);
        Role::firstOrCreate(['nom_role' => 'Enseignant']);
        Role::firstOrCreate(['nom_role' => 'Secrétaire']);

        Utilisateur::firstOrCreate(
            ['email' => 'admin@ecole.test'],
            [
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'permissions' => array_keys(Utilisateur::MODULES),
            ]
        );
    }
}
