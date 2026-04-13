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

        return match($user->role) {
            'admin'      => $this->adminDashboard(),
            'agent'      => $this->agentDashboard($user),
            'supervisor' => $this->supervisorDashboard(),
            'requester'  => $this->requesterDashboard($user),
            default      => redirect()->route('login'),
        };
    }

    private function adminDashboard()
    {
        $stats = [
            'open_tickets'     => Ticket::whereNotIn('status', ['resolved', 'closed'])->count(),
            'resolved_tickets' => Ticket::whereIn('status', ['resolved', 'closed'])->count(),
            'high_priority'    => Ticket::whereIn('priority', ['high', 'critical'])->count(),
            'total_users'      => User::count(),
            'total_categories' => Category::count(),
        ];

        $recentTickets = Ticket::with(['requester', 'assignedUser', 'category'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.admin', compact('stats', 'recentTickets'));
    }

    private function agentDashboard(User $user)
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

        return view('dashboard.agent', compact('stats', 'myTickets'));
    }

    private function supervisorDashboard()
    {
        $stats = [
            'open_tickets'       => Ticket::whereNotIn('status', ['resolved', 'closed'])->count(),
            'high_priority'      => Ticket::whereIn('priority', ['high', 'critical'])->count(),
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
            ->whereIn('priority', ['critical', 'high'])
            ->where('status', 'open')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.supervisor', compact('stats', 'agents', 'escalatedTickets'));
    }

    private function requesterDashboard(User $user)
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

        return view('dashboard.requester', compact('stats', 'myTickets'));
    }
}
