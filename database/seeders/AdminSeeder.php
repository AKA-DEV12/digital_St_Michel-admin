<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création de l'administrateur principal avec les données réelles
        $admin = User::firstOrCreate(
            ['email' => 'adminAGBP@saint-michel-archange.com'],
            [
                'name' => 'Admin Saint Michel',
                'password' => Hash::make('adminAGBP@saint-michel-archange.com'),
                'email_verified_at' => now(),
            ]
        );

        // On s'assure que l'utilisateur a le rôle Super Admin
        // Le rôle est normalement créé par RolePermissionSeeder
        $superAdminRole = Role::where('name', 'Super Admin')->first();

        if ($superAdminRole) {
            $admin->assignRole($superAdminRole);
        }
    }
}
