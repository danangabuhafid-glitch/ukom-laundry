<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebSetting;
use Illuminate\Support\Facades\Storage;

class WebSettingController extends Controller
{
    public function index()
    {
        $setting = WebSetting::first();
        if (!$setting) {
            $setting = WebSetting::create(['app_name' => 'Jeeves Laundry']);
        }
        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $setting = WebSetting::first();

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($setting->logo_path && Storage::disk('public')->exists($setting->logo_path)) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $setting->logo_path = $path;
        }

        $setting->app_name = $request->app_name;
        $setting->save();

        return redirect()->back()->with('success', 'Web settings updated successfully!');
    }
}
