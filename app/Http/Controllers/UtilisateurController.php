<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    public function index()
    {
        $utilisateurs = Utilisateur::with('role')->orderBy('email')->paginate(10);
        return view('utilisateurs.index', compact('utilisateurs'));
    }

    public function create()
    {
        $roles = Role::orderBy('nom_role')->get();
        $modules = Utilisateur::MODULES;
        return view('utilisateurs.create', compact('roles', 'modules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'unique:utilisateurs,email'],
            'password' => ['required', 'string', 'min:6'],
            'role_id' => ['required', 'exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:' . implode(',', array_keys(Utilisateur::MODULES))],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['permissions'] = $data['permissions'] ?? [];

        Utilisateur::create($data);

        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(Utilisateur $utilisateur)
    {
        $roles = Role::orderBy('nom_role')->get();
        $modules = Utilisateur::MODULES;
        return view('utilisateurs.edit', compact('utilisateur', 'roles', 'modules'));
    }

    public function update(Request $request, Utilisateur $utilisateur)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'unique:utilisateurs,email,' . $utilisateur->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role_id' => ['required', 'exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:' . implode(',', array_keys(Utilisateur::MODULES))],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['permissions'] = $data['permissions'] ?? [];

        $utilisateur->update($data);

        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(Utilisateur $utilisateur)
    {
        $utilisateur->delete();
        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur supprimé.');
    }
}
