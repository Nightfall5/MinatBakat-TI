<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;
use App\Models\User;

class AboutController extends Controller
{
    public function index()
    {
        $vision = AppSetting::get('about_vision');
        $mission = AppSetting::get('about_mission');
        $programs = AppSetting::get('about_division_programs');
        $appName = AppSetting::get('app_name', 'MINBAK TI — Talent & Competition Hub');
        $orgName = AppSetting::get('org_name', 'Himpunan Mahasiswa Teknik Informatika');
        $univName = AppSetting::get('univ_name', 'Universitas Teknologi Indonesia');

        // Division Team Leaders & Public Staff
        $officers = User::whereIn('role', ['ketua_divisi', 'staff'])
            ->where('is_active', true)
            ->get();

        return view('public.about', compact('vision', 'mission', 'programs', 'appName', 'orgName', 'univName', 'officers'));
    }

    public function privacy()
    {
        $appName = AppSetting::get('app_name', 'MINBAK TI');
        $orgName = AppSetting::get('org_name', 'Himpunan Mahasiswa Teknik Informatika');
        $contactEmail = AppSetting::get('contact_email', 'minbak.ti@example.ac.id');

        return view('public.privacy', compact('appName', 'orgName', 'contactEmail'));
    }
}
