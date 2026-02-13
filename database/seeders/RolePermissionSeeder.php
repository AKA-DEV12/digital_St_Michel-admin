<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Réinitialiser le cache des rôles et permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Définir les permissions
        $permissions = [
            'access_dashboard',
            'access_reservations',
            'access_rooms',
            'access_time_slots',
            'access_movements',
            'access_activities',
            'access_registrations',
            'access_presences',
            'access_agents',
            'manage_users',
            'manage_roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer les rôles et assigner les permissions existantes
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $managerRole->givePermissionTo([
            'access_dashboard',
            'access_reservations',
            'access_activities',
            'access_registrations',
            'access_presences',
        ]);

        // Assigner le rôle Super Admin au premier utilisateur s'il existe
        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Super Administrateur',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ]);
        }
        $admin->assignRole($superAdminRole);
    }
}
