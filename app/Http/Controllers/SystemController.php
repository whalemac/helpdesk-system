<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function index()
    {
        $settings = [
            'registration_enabled'   => Setting::get('registration_enabled', '1'),
            'default_ticket_priority'=> Setting::get('default_ticket_priority', 'medium'),
            'default_ticket_status'  => Setting::get('default_ticket_status', 'open'),
        ];

        return view('system.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'registration_enabled'    => 'nullable|in:0,1',
            'default_ticket_priority' => 'required|in:low,medium,high,critical',
            'default_ticket_status'   => 'required|in:open,in_progress',
        ]);

        Setting::set('registration_enabled',    $request->has('registration_enabled') ? '1' : '0');
        Setting::set('default_ticket_priority', $request->default_ticket_priority);
        Setting::set('default_ticket_status',   $request->default_ticket_status);

        return redirect()->route('system.index')->with('success', 'System settings saved successfully.');
    }
}
