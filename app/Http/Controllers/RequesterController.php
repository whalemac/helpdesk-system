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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:requesters',
            'phone' => 'required',
            'department' => 'nullable',
        ]);

        Requester::create($request->all());

        return redirect()->route('requesters.index')->with('success', 'Requester added successfully.');
    }

    public function show(Requester $requester)
    {
        return view('requesters.show', compact('requester'));
    }
}
