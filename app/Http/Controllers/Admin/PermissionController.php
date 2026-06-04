<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{
    // ── Vue principale : matrice rôles × permissions ──────
    public function index()
    {
        $roles       = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function ($p) {
            return explode('-', $p->name)[0]; // group by first word
        });
        $users = User::with('roles')->latest()->get();

        return view('admin.permissions.index', compact('roles', 'permissions', 'users'));
    }

    // ── Modifier les permissions d'un rôle ────────────────
    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return back()->with('success', "Permissions du rôle « {$role->name} » mises à jour.");
    }

    // ── Modifier le rôle d'un utilisateur ─────────────────
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        // Ne pas changer le rôle du super admin
        if ($user->hasRole('admin') && $request->role !== 'admin') {
            return back()->withErrors(['Impossible de changer le rôle d\'un administrateur.']);
        }

        $user->syncRoles([$request->role]);
        $user->update(['role' => $request->role]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return back()->with('success', "Rôle de {$user->nom_complet} changé en « {$request->role} ».");
    }

    // ── Donner une permission extra à un user spécifique ──
    public function givePermissionToUser(Request $request, User $user)
    {
        $request->validate([
            'permission' => 'required|string|exists:permissions,name',
        ]);

        $user->givePermissionTo($request->permission);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return back()->with('success', "Permission « {$request->permission} » accordée à {$user->nom_complet}.");
    }

    // ── Retirer une permission d'un user spécifique ───────
    public function revokePermissionFromUser(Request $request, User $user)
    {
        $request->validate([
            'permission' => 'required|string|exists:permissions,name',
        ]);

        $user->revokePermissionTo($request->permission);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return back()->with('success', "Permission « {$request->permission} » retirée à {$user->nom_complet}.");
    }
}
