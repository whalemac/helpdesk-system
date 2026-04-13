<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

class TicketReplyController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        // Access check
        $role = auth()->user()->role;
        if ($role === 'agent' && $ticket->assigned_user_id !== auth()->id()) {
            abort(403);
        }
        if ($role === 'requester' && $ticket->created_by !== auth()->id()) {
            abort(403);
        }

        // Closed tickets cannot receive new replies
        if ($ticket->status === 'closed') {
            return redirect()->back()->with('error', 'This ticket is closed and cannot receive new replies.');
        }

        $request->validate([
            'message'    => 'required|string',
            'reply_type' => 'required|in:public,internal',
        ]);

        TicketReply::create([
            'ticket_id'  => $ticket->id,
            'user_id'    => auth()->id(),
            'message'    => $request->message,
            'reply_type' => $request->reply_type,
        ]);

        // Auto-update ticket status based on who replies
        if ($ticket->status === 'open' && in_array($role, ['admin', 'supervisor', 'agent'])) {
            $ticket->update(['status' => 'in_progress']);
        } elseif ($ticket->status === 'resolved' && $role === 'requester') {
            $ticket->update(['status' => 'open']);
        }

        return redirect()->back()->with('success', 'Reply posted successfully.');
    }
}
