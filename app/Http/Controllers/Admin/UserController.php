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
        ]);

        $validated["password"] = Hash::make($validated["password"]);
        $user = User::create($validated);
        $user->assignRole('admin');

        return redirect()->back()->with("success", "Pengguna Admin berhasil ditambahkan.");
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users,email," . $user->id,
            "password" => ["nullable", "confirmed", Password::defaults()],
        ]);

        if (empty($validated["password"])) {
            unset($validated["password"]);
        } else {
            $validated["password"] = Hash::make($validated["password"]);
        }

        $user->update($validated);

        return redirect()->back()->with("success", "Pengguna Admin berhasil diperbarui.");
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

