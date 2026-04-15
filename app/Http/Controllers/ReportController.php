<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'supervisor'])) {
            abort(403, 'Unauthorized access to Reporting Dashboard.');
        }

        $query = Ticket::query();
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $totalTickets    = (clone $query)->count();
        $openTickets     = (clone $query)->whereNotIn('status', ['resolved', 'closed'])->count();
        $resolvedTickets = (clone $query)->whereIn('status', ['resolved', 'closed'])->count();

        $ticketsByCategory = Category::withCount(['tickets' => function ($q) use ($request) {
            if ($request->date_from) $q->whereDate('created_at', '>=', $request->date_from);
            if ($request->date_to)   $q->whereDate('created_at', '<=', $request->date_to);
        }])->get();

        $ticketsByStatus = (clone $query)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $ticketsByPriority = (clone $query)
            ->select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get();

        return view('reports.index', compact(
            'totalTickets', 'openTickets', 'resolvedTickets',
            'ticketsByCategory', 'ticketsByStatus', 'ticketsByPriority'
        ));
    }

    public function exportPdf(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'supervisor'])) {
            abort(403);
        }

        $query = Ticket::with(['requester', 'category', 'assignedUser']);
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $tickets         = $query->latest()->get();
        $totalTickets    = $tickets->count();
        $openTickets     = $tickets->whereIn('status', ['open', 'in_progress', 'pending'])->count();
        $resolvedTickets = $tickets->whereIn('status', ['resolved', 'closed'])->count();
        $dateFrom        = $request->date_from ?? 'All time';
        $dateTo          = $request->date_to ?? now()->format('Y-m-d');

        $ticketsByCategory = Category::withCount(['tickets' => function ($q) use ($request) {
            if ($request->date_from) $q->whereDate('created_at', '>=', $request->date_from);
            if ($request->date_to)   $q->whereDate('created_at', '<=', $request->date_to);
        }])->get();

        $pdf = Pdf::loadView('reports.pdf', compact(
            'tickets', 'totalTickets', 'openTickets', 'resolvedTickets',
            'dateFrom', 'dateTo', 'ticketsByCategory'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('helpdesk-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
