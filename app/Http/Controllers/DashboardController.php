<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;
        $stats = [];

        if ($role === 'admin' || $role === 'supervisor') {
            $stats = [
                'open_tickets' => Ticket::where('status', 'open')->count(),
                'resolved_tickets' => Ticket::where('status', 'resolved')->count(),
                'high_priority' => Ticket::where('priority', 'high')->count(),
            ];
        } elseif ($role === 'agent') {
            $stats = [
                'assigned_tickets' => Ticket::where('assigned_user_id', $user->id)->count(),
                'resolved_tickets' => Ticket::where('assigned_user_id', $user->id)->where('status', 'resolved')->count(),
            ];
        } elseif ($role === 'requester') {
            $stats = [
                'my_tickets' => Ticket::where('created_by', $user->id)->count(),
                'my_open_tickets' => Ticket::where('created_by', $user->id)->where('status', 'open')->count(),
            ];
        }

        return view('dashboard', compact('role', 'stats'));
    }
}
