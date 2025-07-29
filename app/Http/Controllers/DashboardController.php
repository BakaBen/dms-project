<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik berdasarkan status
        $status = [
            'unvalidated' => Document::where('status', 'unvalidated')->count(),
            'approved'    => Document::where('status', 'approved')->count(),
            'rejected'    => Document::where('status', 'rejected')->count(),
        ];

        // Ambil awal & akhir bulan sekarang
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();

        // Ambil semua data dokumen di bulan ini
        $documents = Document::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();

        // Kelompokkan berdasarkan minggu keberapa (1-5)
        $weeklyData = collect(range(1, 5))->mapWithKeys(function ($week) {
            return ["Week-$week" => 0];
        });

        foreach ($documents as $doc) {
            $weekOfMonth = intval(ceil($doc->created_at->day / 7)); // Hitung minggu keberapa
            $label = "Week-$weekOfMonth";

            if ($weeklyData->has($label)) {
                $weeklyData[$label] += 1;
            }
        }

        // Siapkan untuk chart
        $labels = $weeklyData->keys();
        $data = $weeklyData->values();

        $roles = Role::withCount('users')->get()->pluck('users_count', 'name');
        
        return view('dashboard', compact('status', 'labels', 'data', 'roles'));
    }
}
