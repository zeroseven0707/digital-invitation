<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingsWebController extends Controller
{
    public function index()
    {
        $groups = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.settings.index', compact('groups'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if (!$setting) continue;

            // Skip secret jika kosong atau masked
            if ($setting->type === 'secret' && (empty($value) || str_contains($value, '***'))) {
                continue;
            }

            $setting->update(['value' => $value ?? '']);
        }

        Setting::clearCache();

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
