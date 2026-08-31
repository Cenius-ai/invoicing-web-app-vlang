<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalInvoices = Invoice::count();
        $totalRevenue = Invoice::sum('amount');
        $paidCount = Invoice::where('status', 'paid')->count();
        $overdueCount = Invoice::where('status', 'overdue')->count();
        $draftCount = Invoice::where('status', 'draft')->count();
        $sentCount = Invoice::where('status', 'sent')->count();
        $recentInvoices = Invoice::with('client')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalInvoices', 'totalRevenue', 'paidCount',
            'overdueCount', 'draftCount', 'sentCount', 'recentInvoices'
        ));
    }
}
