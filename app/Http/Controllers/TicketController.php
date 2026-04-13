<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\Requester;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;
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
        $categories = Category::where('status', 'active')->get();
        $requesters = Requester::all();
        $agents = User::whereIn('role', ['admin', 'supervisor', 'agent'])->get();

        return view('tickets.create', compact('categories', 'requesters', 'agents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'requester_id'     => 'required|exists:requesters,id',
            'category_id'      => 'required|exists:categories,id',
            'subject'          => 'required|max:255',
            'description'      => 'required',
            'priority'         => 'required|in:low,medium,high,critical',
            'status'           => 'required|in:open,in_progress,pending,resolved,closed',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();

        Ticket::create($data);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        $role = auth()->user()->role;

        if ($role === 'agent' && $ticket->assigned_user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        } elseif ($role === 'requester' && $ticket->created_by !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        $ticket->load(['requester', 'category', 'assignedUser', 'replies.user']);
        $agents = User::whereIn('role', ['admin', 'supervisor', 'agent'])->get();

        return view('tickets.show', compact('ticket', 'agents'));
    }

    public function edit(Ticket $ticket)
    {
        // Closed tickets cannot be edited
        if ($ticket->status === 'closed') {
            return redirect()->route('tickets.show', $ticket)
                ->with('error', 'Closed tickets cannot be edited. Please reopen the ticket first.');
        }

        // Only admin/supervisor can edit any ticket; agents can edit their assigned tickets
        $role = auth()->user()->role;
        if ($role === 'requester') {
            abort(403, 'Requesters cannot edit tickets.');
        }
        if ($role === 'agent' && $ticket->assigned_user_id !== auth()->id()) {
            abort(403, 'You can only edit your assigned tickets.');
        }

        $categories = Category::where('status', 'active')->get();
        $requesters = Requester::all();
        $agents = User::whereIn('role', ['admin', 'supervisor', 'agent'])->get();

        return view('tickets.edit', compact('ticket', 'categories', 'requesters', 'agents'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        if ($ticket->status === 'closed') {
            return redirect()->back()->with('error', 'Closed tickets cannot be edited.');
        }

        $role = auth()->user()->role;
        if ($role === 'requester') {
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
        if ($ticket->status === 'closed') {
            return redirect()->back()->with('error', 'This ticket is closed. Reopen it to change status.');
        }

        $request->validate([
            'status' => 'required|in:open,in_progress,pending,resolved,closed',
        ]);

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

        return redirect()->back()->with('success', 'Ticket reassigned successfully.');
    }
}
