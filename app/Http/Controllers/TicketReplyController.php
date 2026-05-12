<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

class TicketReplyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ticket_id'  => 'required|exists:tickets,id',
            'message'    => 'required|string',
            'reply_type' => 'required|in:reply,internal_note',
        ]);

        $ticket = Ticket::findOrFail($request->ticket_id);

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

        $reply = TicketReply::create([
            'ticket_id'  => $ticket->id,
            'user_id'    => $user->id,
            'message'    => $request->message,
            'reply_type' => $request->reply_type,
        ]);

        // Smart status transitions
        if ($ticket->status === 'open' && in_array($user->role, ['admin', 'supervisor', 'agent'])) {
            $ticket->update(['status' => 'in_progress']);
        } elseif ($ticket->status === 'resolved' && $user->role === 'requester') {
            $ticket->update(['status' => 'open']);
        }

        return redirect()->route('tickets.show', $ticket->id)
                         ->with('success', 'Reply added.');
    }
}
