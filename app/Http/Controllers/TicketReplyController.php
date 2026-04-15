<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

class TicketReplyController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        // Authorization: admin/supervisor can reply on any ticket;
        // agents only on their assigned tickets; requesters only on their own tickets.
        $user    = auth()->user();
        $allowed = in_array($user->role, ['admin', 'supervisor'])
                   || ($user->role === 'agent' && $ticket->assigned_user_id === $user->id)
                   || ($user->role === 'requester' && $ticket->created_by === $user->id);

        if (!$allowed) {
            abort(403, 'You are not authorized to reply to this ticket.');
        }

        // Closed tickets cannot receive replies
        if ($ticket->status === 'closed') {
            return redirect()->back()->with('error', 'This ticket is closed and cannot receive new replies.');
        }

        $request->validate([
            'message'    => 'required|string',
            'reply_type' => 'required|in:public,internal',
        ]);

        $reply = TicketReply::create([
            'ticket_id'  => $ticket->id,
            'user_id'    => $user->id,   // always from auth, never from form input
            'message'    => $request->message,
            'reply_type' => $request->reply_type,
        ]);

        // Smart status transitions
        if ($ticket->status === 'open' && in_array($user->role, ['admin', 'supervisor', 'agent'])) {
            $ticket->update(['status' => 'in_progress']);
        } elseif ($ticket->status === 'resolved' && $user->role === 'requester') {
            $ticket->update(['status' => 'open']);
        }

        return redirect()->route('tickets.show', $reply->ticket_id)
                         ->with('success', 'Reply added successfully.');
    }
}
