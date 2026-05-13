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

    public function exportCsv(Request $request)
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

        $tickets = $query->latest()->get();

        $filename = "helpdesk-report-" . now()->format('Y-m-d') . ".csv";
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Ticket ID', 'Subject', 'Requester', 'Category', 'Priority', 'Status', 'Assigned To', 'Created At');

        $callback = function() use($tickets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tickets as $ticket) {
                $row['Ticket ID']   = $ticket->id;
                $row['Subject']     = $ticket->subject;
                $row['Requester']   = ($ticket->requester->first_name ?? '—') . ' ' . ($ticket->requester->last_name ?? '');
                $row['Category']    = $ticket->category->name ?? 'N/A';
                $row['Priority']    = strtoupper($ticket->priority);
                $row['Status']      = strtoupper(str_replace('_', ' ', $ticket->status));
                $row['Assigned To'] = $ticket->assignedUser->name ?? 'Unassigned';
                $row['Created At']  = $ticket->created_at->format('Y-m-d H:i');

                fputcsv($file, array(
                    $row['Ticket ID'],
                    $row['Subject'],
                    $row['Requester'],
                    $row['Category'],
                    $row['Priority'],
                    $row['Status'],
                    $row['Assigned To'],
                    $row['Created At']
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
