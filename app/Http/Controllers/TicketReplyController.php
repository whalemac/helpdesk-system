<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

class TicketReplyController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'reply_type' => 'nullable|string|in:public,internal',
        ]);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'reply_type' => $request->reply_type ?? 'public'
        ]);

        // Automatically update ticket status based on who replies
        if ($ticket->status === 'open' && in_array(auth()->user()->role, ['admin', 'supervisor', 'agent'])) {
            $ticket->update(['status' => 'in_progress']);
        } elseif ($ticket->status === 'resolved' && auth()->user()->role === 'requester') {
            $ticket->update(['status' => 'open']); // re-open ticket if requester replies
        }

        return redirect()->back()->with('success', 'Reply posted successfully.');
    }
}
