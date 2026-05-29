<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string'
        ]);

        foreach ($data['settings'] as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        // Clear settings cache if we implement it, but for now just flashing success
        
        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
