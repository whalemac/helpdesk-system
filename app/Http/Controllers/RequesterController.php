<?php

namespace App\Http\Controllers;

use App\Models\Requester;
use Illuminate\Http\Request;

class RequesterController extends Controller
{
    public function index()
    {
        $requesters = Requester::latest()->get();
        return view('requesters.index', compact('requesters'));
    }

    public function create()
    {
        return view('requesters.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:requesters,email',
            'phone'      => 'required|string|max:20',
            'department' => 'nullable|string|max:255',
        ]);

        Requester::create($request->all());

        return redirect()->route('requesters.index')->with('success', 'Requester added successfully.');
    }

    public function show(Requester $requester)
    {
        $requester->load('tickets');
        return view('requesters.show', compact('requester'));
    }

    public function edit(Requester $requester)
    {
        return view('requesters.edit', compact('requester'));
    }

    public function update(Request $request, Requester $requester)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:requesters,email,' . $requester->id,
            'phone'      => 'required|string|max:20',
            'department' => 'nullable|string|max:255',
        ]);

        $requester->update($request->all());

        return redirect()->route('requesters.show', $requester)->with('success', 'Requester updated.');
    }

    public function destroy(Requester $requester)
    {
        if ($requester->tickets()->count() > 0) {
            return redirect()->route('requesters.index')
                ->with('error', 'Cannot delete a requester with existing tickets.');
        }

        $requester->delete();

        return redirect()->route('requesters.index')->with('success', 'Requester deleted.');
    }
}
