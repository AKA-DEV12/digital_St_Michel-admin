<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    public function export(Request $request, \App\Services\ExportService $exportService)
    {
        $agents = Agent::latest()->get();

        return $exportService->export(
            $request,
            'Agents / Utilisateurs App Mobile',
            'agents_' . date('Y-m-d'),
            ['ID', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Créé le'],
            $agents,
            function ($agent) {
                return [
                    $agent->id,
                    $agent->nom,
                    $agent->prenom,
                    $agent->email,
                    $agent->phone,
                    $agent->created_at ? $agent->created_at->format('Y-m-d H:i') : ''
                ];
            }
        );
    }

    public function index()
    {
        $agents = Agent::latest()->paginate(15);
        return view('admin.agents.index', compact('agents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:agents,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ], [
            'password.required' => 'Le mot de passe est requis',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères',
        ]);

        Agent::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Agent créé avec succès.');
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:agents,email,' . $agent->id,
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $agent->update($data);

        return back()->with('success', 'Agent mis à jour avec succès.');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();
        return back()->with('success', 'Agent supprimé avec succès.');
    }
}