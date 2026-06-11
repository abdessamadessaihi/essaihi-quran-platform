<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Family;
use App\Models\Khatma;
use App\Models\Revision;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'         => User::count(),
            'active_families'     => Family::where('is_active', true)->count(),
            'total_families'      => Family::count(),
            'active_khatmas'      => Khatma::where('status', 'active')->count(),
            'total_khatmas'       => Khatma::count(),
            'completed_revisions' => Revision::where('status', 'completed')->count(),
        ];

        $latestUsers = User::latest()->take(5)->get();
        $latestFamilies = Family::with('creator')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestUsers', 'latestFamilies'));
    }
}