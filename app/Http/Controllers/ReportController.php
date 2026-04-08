<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Only Admin and Supervisor have access
        if (!in_array(auth()->user()->role, ['admin', 'supervisor'])) {
            abort(403, 'Unauthorized access to Reporting Dashboard.');
        }

        $totalTickets = Ticket::count();
        $openTickets = Ticket::whereNotIn('status', ['resolved', 'closed'])->count();
        $resolvedTickets = Ticket::whereIn('status', ['resolved', 'closed'])->count();

        $ticketsByCategory = Category::withCount('tickets')->get();
        
        $ticketsByStatus = Ticket::select('status', DB::raw('count(*) as count'))
                                 ->groupBy('status')
                                 ->get();
                                 
        $ticketsByPriority = Ticket::select('priority', DB::raw('count(*) as count'))
                                 ->groupBy('priority')
                                 ->get();

        return view('reports.index', compact(
            'totalTickets', 'openTickets', 'resolvedTickets', 
            'ticketsByCategory', 'ticketsByStatus', 'ticketsByPriority'
        ));
    }
}
