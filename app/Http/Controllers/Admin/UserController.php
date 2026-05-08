<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function export(Request $request, \App\Services\ExportService $exportService)
    {
        $users = User::with('roles')->latest()->get();

        return $exportService->export(
            $request,
            'Administrateurs de la Plateforme',
            'admins_' . date('Y-m-d'),
            ['ID', 'Nom', 'Email', 'Rôle(s)', 'Créé le'],
            $users,
            function ($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->roles->pluck('name')->join(', '),
                    $user->created_at ? $user->created_at->format('Y-m-d H:i') : ''
                ];
            }
        );
    }

    public function index()
    {
        $users = User::with('roles', 'group')->latest()->paginate(15);
        $roles = Role::all();
        $groups = Group::orderBy('nom_groupe')->get();
        return view('admin.users.index', compact('users', 'roles', 'groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'roles' => 'required|array',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'group_id' => $validated['group_id'] ?? null,
        ]);

        $user->assignRole($validated['roles']);

        return back()->with('success', 'Utilisateur créé avec succès.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'roles' => 'required|array',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'group_id' => $validated['group_id'] ?? null,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->syncRoles($validated['roles']);

        return back()->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();
        return back()->with('success', 'Utilisateur supprimé avec succès.');
    }
}
