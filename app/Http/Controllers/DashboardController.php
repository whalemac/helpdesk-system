<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Required variables always passed to every dashboard view
        $totalTickets = Ticket::count();
        $openTickets  = Ticket::where('status', 'open')->count();
        $inProgress   = Ticket::where('status', 'in_progress')->count();
        $resolved     = Ticket::whereIn('status', ['resolved', 'closed'])->count();
        $highPriority = Ticket::whereIn('priority', ['high', 'urgent'])->count();

        return match($user->role) {
            'admin'      => $this->adminDashboard($totalTickets, $openTickets, $inProgress, $resolved, $highPriority),
            'agent'      => $this->agentDashboard($user, $totalTickets, $openTickets, $inProgress, $resolved, $highPriority),
            'supervisor' => $this->supervisorDashboard($totalTickets, $openTickets, $inProgress, $resolved, $highPriority),
            'requester'  => $this->requesterDashboard($user, $totalTickets, $openTickets, $inProgress, $resolved, $highPriority),
            default      => redirect()->route('login'),
        };
    }

    private function adminDashboard(int $totalTickets, int $openTickets, int $inProgress, int $resolved, int $highPriority)
    {
        $stats = [
            'open_tickets'     => Ticket::whereNotIn('status', ['resolved', 'closed'])->count(),
            'resolved_tickets' => Ticket::whereIn('status', ['resolved', 'closed'])->count(),
            'high_priority'    => Ticket::whereIn('priority', ['high', 'urgent'])->count(),
            'total_users'      => User::count(),
            'total_categories' => Category::count(),
        ];

        $recentTickets = Ticket::with(['requester', 'assignedUser', 'category'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.admin', compact('stats', 'recentTickets', 'totalTickets', 'openTickets', 'inProgress', 'resolved', 'highPriority'));
    }

    private function agentDashboard(User $user, int $totalTickets, int $openTickets, int $inProgress, int $resolved, int $highPriority)
    {
        $stats = [
            'open_assigned'     => Ticket::where('assigned_user_id', $user->id)
                                         ->where('status', 'open')->count(),
            'in_progress'       => Ticket::where('assigned_user_id', $user->id)
                                         ->where('status', 'in_progress')->count(),
            'resolved_today'    => Ticket::where('assigned_user_id', $user->id)
                                         ->where('status', 'resolved')
                                         ->whereDate('updated_at', Carbon::today())
                                         ->count(),
        ];

        $myTickets = Ticket::with(['requester', 'category'])
            ->where('assigned_user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.agent', compact('stats', 'myTickets', 'totalTickets', 'openTickets', 'inProgress', 'resolved', 'highPriority'));
    }

    private function supervisorDashboard(int $totalTickets, int $openTickets, int $inProgress, int $resolved, int $highPriority)
    {
        $stats = [
            'open_tickets'       => Ticket::whereNotIn('status', ['resolved', 'closed'])->count(),
            'high_priority'      => Ticket::whereIn('priority', ['high', 'urgent'])->count(),
            'resolved_this_week' => Ticket::whereIn('status', ['resolved', 'closed'])
                                          ->whereBetween('updated_at', [
                                              Carbon::now()->startOfWeek(),
                                              Carbon::now()->endOfWeek(),
                                          ])->count(),
        ];

        // Per-agent breakdown
        $agents = User::whereIn('role', ['agent', 'supervisor'])
            ->withCount([
                'assignedTickets',
                'assignedTickets as resolved_tickets_count' => fn($q) =>
                    $q->whereIn('status', ['resolved', 'closed']),
            ])
            ->get();

        // Escalated tickets: critical or high priority that are still open
        $escalatedTickets = Ticket::with(['requester', 'assignedUser'])
            ->whereIn('priority', ['urgent', 'high'])
            ->where('status', 'open')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.supervisor', compact('stats', 'agents', 'escalatedTickets', 'totalTickets', 'openTickets', 'inProgress', 'resolved', 'highPriority'));
    }

    private function requesterDashboard(User $user, int $totalTickets, int $openTickets, int $inProgress, int $resolved, int $highPriority)
    {
        $stats = [
            'total_submitted' => Ticket::where('created_by', $user->id)->count(),
            'pending_tickets' => Ticket::where('created_by', $user->id)
                                       ->whereIn('status', ['open', 'pending'])->count(),
            'resolved_tickets'=> Ticket::where('created_by', $user->id)
                                       ->whereIn('status', ['resolved', 'closed'])->count(),
        ];

        $myTickets = Ticket::with(['category'])
            ->where('created_by', $user->id)
            ->latest()
            ->get();

        return view('dashboard.requester', compact('stats', 'myTickets', 'totalTickets', 'openTickets', 'inProgress', 'resolved', 'highPriority'));
    }
}
