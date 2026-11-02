<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppSetting;
use App\Models\AuditLog;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = AppSetting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:150',
            'app_short_name' => 'required|string|max:50',
            'org_name' => 'required|string|max:150',
            'univ_name' => 'required|string|max:150',
            'contact_email' => 'required|email|max:150',
            'contact_phone' => 'nullable|string|max:50',
            'instagram_handle' => 'nullable|string|max:50',
            'instagram_url' => 'nullable|url|max:255',
            'about_vision' => 'nullable|string',
            'about_mission' => 'nullable|string',
            'about_division_programs' => 'nullable|string',
            'scoring_weight_interest' => 'required|numeric|min:0|max:100',
            'scoring_weight_activity' => 'required|numeric|min:0|max:100',
            'scoring_weight_experience' => 'required|numeric|min:0|max:100',
            'scoring_weight_learning' => 'required|numeric|min:0|max:100',
        ]);

        // Validate sum of weights = 100%
        $sum = $validated['scoring_weight_interest'] + 
               $validated['scoring_weight_activity'] + 
               $validated['scoring_weight_experience'] + 
               $validated['scoring_weight_learning'];

        if (abs($sum - 100) > 0.01) {
            return back()->with('error', "Total bobot penilaian harus tepat 100%. Saat ini: {$sum}%.")->withInput();
        }

        foreach ($validated as $key => $val) {
            AppSetting::set($key, is_string($val) ? strip_tags($val) : (string)$val);
        }

        AuditLog::log('superadmin_update_settings', "Memperbarui konfigurasi sistem dan bobot penilaian", 'app_settings');

        return back()->with('success', 'Konfigurasi aplikasi berhasil diperbarui.');
    }
}
