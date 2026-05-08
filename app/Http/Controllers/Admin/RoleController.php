<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $allPermissions = Permission::all();
        
        $permissionGroups = [
            'Général' => [
                'access_dashboard',
                'access_settings',
                'manage_users',
                'manage_roles',
                'access_agents',
            ],
            'Réservations' => [
                'access_reservations',
                'access_rooms',
                'access_time_slots',
            ],
            'Évènements & Inscriptions' => [
                'access_activities',
                'access_registrations',
                'access_presences',
                'access_groups',
                'access_group_members',
                'access_movements',
            ],
            'Contenu' => [
                'access_blog',
                'access_flash_messages',
                'access_priests',
            ],
            'Services' => [
                'access_mass_requests',
            ],
        ];

        // Traduction des permissions
        $translations = [
            'access_dashboard' => 'Tableau de bord',
            'access_reservations' => 'Réservations',
            'access_rooms' => 'Gestion des salles',
            'access_time_slots' => 'Créneaux horaires',
            'access_movements' => 'Mouvements & Groupes',
            'access_activities' => 'Activités',
            'access_registrations' => 'Inscriptions',
            'access_presences' => 'Pointage/Présences',
            'access_agents' => 'Gestion des agents',
            'access_blog' => 'Blog & Actualités',
            'access_flash_messages' => 'Messages flash',
            'access_settings' => 'Paramètres du site',
            'access_priests' => 'Gestion des prêtres',
            'access_groups' => 'Gestion des groupes (Liste)',
            'access_group_members' => 'Gestion des groupes (Membres)',
            'manage_users' => 'Gestion des administrateurs',
            'manage_roles' => 'Gestion des rôles & droits',
            'access_mass_requests' => 'Demandes de messe',
        ];

        return view('admin.roles.index', compact('roles', 'permissionGroups', 'translations', 'allPermissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->givePermissionTo($validated['permissions']);

        return back()->with('success', 'Rôle créé avec succès.');
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return back()->with('success', 'Rôle mis à jour avec succès.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Le rôle Super Admin ne peut pas être supprimé.');
        }

        $role->delete();
        return back()->with('success', 'Rôle supprimé avec succès.');
    }
}
