<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with("roles")->latest()->get();
        $roles = Role::all();
        
        return Inertia::render("Admin/Users/Index", [
            "users" => $users,
            "roles" => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users",
            "password" => ["required", "confirmed", Password::defaults()],
            "role_id" => "required|exists:roles,id",
        ]);

        $validated["password"] = Hash::make($validated["password"]);
        
        $user = User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "password" => $validated["password"],
        ]);

        $role = Role::findOrFail($validated["role_id"]);
        $user->assignRole($role->name);

        return redirect()->back()->with("success", "Pengguna berhasil ditambahkan.");
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users,email," . $user->id,
            "password" => ["nullable", "confirmed", Password::defaults()],
            "role_id" => "required|exists:roles,id",
        ]);

        if (empty($validated["password"])) {
            unset($validated["password"]);
        } else {
            $validated["password"] = Hash::make($validated["password"]);
        }

        $user->update($validated);

        $role = Role::findOrFail($validated["role_id"]);
        $user->syncRoles([$role->name]);

        return redirect()->back()->with("success", "Pengguna berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with("error", "Anda tidak dapat menghapus akun Anda sendiri.");
        }

        $user->delete();

        return redirect()->back()->with("success", "Pengguna berhasil dihapus.");
    }
}

