<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\Category;
use App\Models\Requester;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $role  = auth()->user()->role;
        $query = Ticket::with(['requester', 'category', 'assignedUser']);

        if ($role === 'agent') {
            $query->where('assigned_user_id', auth()->id());
        } elseif ($role === 'requester') {
            $query->where('created_by', auth()->id());
        }

        $tickets = $query->latest()->get();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        // Admins cannot create tickets
        if (auth()->user()->role === 'admin') {
            abort(403, 'Admins cannot create tickets. Use the Requester or Agent accounts.');
        }

        $categories = Category::where('status', 'active')->get();
        $requesters = Requester::all();
        $agents     = User::whereIn('role', ['admin', 'supervisor', 'agent'])->get();

        return view('tickets.create', compact('categories', 'requesters', 'agents'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'admin') {
            abort(403);
        }

        $request->validate([
            'requester_id'     => 'required|exists:requesters,id',
            'category_id'      => 'required|exists:categories,id',
            'subject'          => 'required|max:255',
            'description'      => 'required',
            'priority'         => 'required|in:low,medium,high,critical',
            'status'           => 'required|in:open,in_progress,pending,resolved,closed',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $data                = $request->all();
        $data['created_by']  = auth()->id();

        Ticket::create($data);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        $role = auth()->user()->role;

        if ($role === 'agent' && $ticket->assigned_user_id !== auth()->id()) {
            abort(403, 'You can only view your assigned tickets.');
        } elseif ($role === 'requester' && $ticket->created_by !== auth()->id()) {
            abort(403, 'You can only view your own tickets.');
        }

        $ticket->load(['requester', 'category', 'assignedUser', 'replies.user']);
        $agents = User::where('role', 'agent')->get();

        return view('tickets.show', compact('ticket', 'agents'));
    }

    public function edit(Ticket $ticket)
    {
        if ($ticket->status === 'closed') {
            return redirect()->route('tickets.show', $ticket)
                ->with('error', 'Closed tickets cannot be edited. Reopen the ticket first.');
        }

        $role = auth()->user()->role;
        if ($role === 'admin' || $role === 'requester') {
            abort(403, 'You cannot edit tickets.');
        }
        if ($role === 'agent' && $ticket->assigned_user_id !== auth()->id()) {
            abort(403, 'You can only edit your assigned tickets.');
        }

        $categories = Category::where('status', 'active')->get();
        $requesters = Requester::all();
        $agents     = User::whereIn('role', ['admin', 'supervisor', 'agent'])->get();

        return view('tickets.edit', compact('ticket', 'categories', 'requesters', 'agents'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        if ($ticket->status === 'closed') {
            return redirect()->back()->with('error', 'Closed tickets cannot be edited.');
        }

        $role = auth()->user()->role;
        if ($role === 'admin' || $role === 'requester') {
            abort(403);
        }

        $request->validate([
            'requester_id'     => 'required|exists:requesters,id',
            'category_id'      => 'required|exists:categories,id',
            'subject'          => 'required|max:255',
            'description'      => 'required',
            'priority'         => 'required|in:low,medium,high,critical',
            'status'           => 'required|in:open,in_progress,pending,resolved,closed',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $ticket->update($request->all());

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket updated successfully.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $user = auth()->user();
        $role = $user->role;

        // Agents can only update tickets assigned to them
        if ($role === 'agent' && $ticket->assigned_user_id !== $user->id) {
            abort(403, 'You can only update the status of tickets assigned to you.');
        }

        // Requesters cannot update status at all
        if ($role === 'requester') {
            abort(403, 'Requesters cannot update ticket status.');
        }

        $request->validate([
            'status' => 'required|in:open,in_progress,pending,resolved,closed',
        ]);

        // Closed tickets can only be re-opened
        if ($ticket->status === 'closed' && $request->status !== 'open') {
            return back()->withErrors(['status' => 'A closed ticket can only be re-opened, not changed to another status.']);
        }

        $ticket->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Ticket status updated.');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        if (!in_array(auth()->user()->role, ['admin', 'supervisor'])) {
            abort(403, 'Only Supervisors and Admins can reassign tickets.');
        }

        $request->validate([
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $ticket->update(['assigned_user_id' => $request->assigned_user_id]);

        return redirect()->back()->with('success', 'Ticket assignment updated.');
    }

    public function reassign(Request $request, Ticket $ticket)
    {
        if (!in_array(auth()->user()->role, ['admin', 'supervisor'])) {
            abort(403, 'Only Admins and Supervisors can reassign tickets.');
        }

        $request->validate([
            'assigned_user_id' => 'required|exists:users,id',
        ]);

        $newAgent = User::findOrFail($request->assigned_user_id);
        $ticket->update(['assigned_user_id' => $request->assigned_user_id]);

        // Log as internal note
        TicketReply::create([
            'ticket_id'  => $ticket->id,
            'user_id'    => auth()->id(),
            'message'    => 'Ticket reassigned to ' . $newAgent->name . ' by ' . auth()->user()->name . '.',
            'reply_type' => 'internal',
        ]);

        return redirect()->route('tickets.show', $ticket)
                         ->with('success', 'Ticket reassigned to ' . $newAgent->name . '.');
    }
}
