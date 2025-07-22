<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $unvalidated = Document::where('status', 'unvalidated')->count();
        $approved = Document::where('status', 'approved')->count();
        $rejected = Document::where('status', 'rejected')->count();

        return view('dashboard', compact('unvalidated', 'approved', 'rejected'));
    }
}
