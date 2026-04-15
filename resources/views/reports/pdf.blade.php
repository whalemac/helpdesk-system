<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; font-size: 11px; color: #1f2937; background: #fff; }

        .header { background: #1e293b; color: #fff; padding: 24px 32px; margin-bottom: 24px; }
        .header h1 { font-size: 20px; font-weight: 700; letter-spacing: 1px; }
        .header p { font-size: 11px; color: #94a3b8; margin-top: 4px; }

        .kpi-grid { display: table; width: 100%; margin: 0 32px 24px 32px; width: calc(100% - 64px); }
        .kpi-cell { display: table-cell; width: 33%; text-align: center; padding: 16px 12px; border: 1px solid #e5e7eb; border-radius: 8px; }
        .kpi-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #6b7280; }
        .kpi-value { font-size: 32px; font-weight: 900; margin-top: 6px; }
        .kpi-blue   .kpi-value { color: #1d4ed8; }
        .kpi-amber  .kpi-value { color: #b45309; }
        .kpi-green  .kpi-value { color: #047857; }

        .section { margin: 0 32px 24px 32px; }
        .section-title { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #6b7280; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; margin-bottom: 12px; }

        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th { background: #f8fafc; text-align: left; padding: 8px 10px; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
        td { padding: 8px 10px; border-bottom: 1px solid #f1f5f9; color: #374151; }
        tr:last-child td { border-bottom: none; }
        tr:nth-child(even) td { background: #f9fafb; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-open      { background: #dbeafe; color: #1d4ed8; }
        .badge-in_progress{ background: #fef3c7; color: #92400e; }
        .badge-pending   { background: #ede9fe; color: #6d28d9; }
        .badge-resolved  { background: #d1fae5; color: #065f46; }
        .badge-closed    { background: #f3f4f6; color: #374151; }
        .priority-critical{ color: #b91c1c; font-weight: 900; }
        .priority-high   { color: #c2410c; font-weight: 700; }
        .priority-medium { color: #1d4ed8; }
        .priority-low    { color: #6b7280; }

        .footer { margin: 24px 32px 0 32px; border-top: 1px solid #e5e7eb; padding-top: 12px; font-size: 9px; color: #9ca3af; display: table; width: calc(100% - 64px); }
        .footer-left  { display: table-cell; text-align: left; }
        .footer-right { display: table-cell; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Helpdesk Ticket Report</h1>
        <p>
            Period: {{ $dateFrom }} — {{ $dateTo }}
            &nbsp;&nbsp;|&nbsp;&nbsp;
            Generated: {{ now()->format('F d, Y h:i A') }}
            &nbsp;&nbsp;|&nbsp;&nbsp;
            By: {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})
        </p>
    </div>

    {{-- KPI Summary --}}
    <div class="kpi-grid">
        <table width="100%"><tr>
            <td style="width:33%; padding:12px; text-align:center; border:1px solid #e5e7eb; border-radius:6px;">
                <div class="kpi-label">Total Tickets</div>
                <div style="font-size:28px; font-weight:900; color:#1d4ed8; margin-top:4px;">{{ $totalTickets }}</div>
            </td>
            <td width="2%"></td>
            <td style="width:33%; padding:12px; text-align:center; border:1px solid #e5e7eb; border-radius:6px;">
                <div class="kpi-label">Open / Active</div>
                <div style="font-size:28px; font-weight:900; color:#b45309; margin-top:4px;">{{ $openTickets }}</div>
            </td>
            <td width="2%"></td>
            <td style="width:33%; padding:12px; text-align:center; border:1px solid #e5e7eb; border-radius:6px;">
                <div class="kpi-label">Resolved / Closed</div>
                <div style="font-size:28px; font-weight:900; color:#047857; margin-top:4px;">{{ $resolvedTickets }}</div>
            </td>
        </tr></table>
    </div>

    {{-- Category Breakdown --}}
    <div class="section">
        <div class="section-title">Tickets by Category</div>
        <table>
            <thead><tr>
                <th>Category</th>
                <th style="text-align:right;">Ticket Count</th>
            </tr></thead>
            <tbody>
                @forelse($ticketsByCategory as $cat)
                    <tr>
                        <td>{{ $cat->name }}</td>
                        <td style="text-align:right; font-weight:700;">{{ $cat->tickets_count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" style="color:#9ca3af; text-align:center; padding:16px;">No data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Full Ticket List --}}
    <div class="section">
        <div class="section-title">Full Ticket List ({{ $totalTickets }} records)</div>
        <table>
            <thead><tr>
                <th>#</th>
                <th>Subject</th>
                <th>Requester</th>
                <th>Category</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Assigned To</th>
                <th>Created</th>
            </tr></thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($ticket->subject, 35) }}</td>
                        <td>{{ $ticket->requester->first_name ?? '—' }} {{ $ticket->requester->last_name ?? '' }}</td>
                        <td>{{ $ticket->category->name ?? '—' }}</td>
                        <td class="priority-{{ $ticket->priority }}">{{ strtoupper($ticket->priority) }}</td>
                        <td><span class="badge badge-{{ $ticket->status }}">{{ str_replace('_', ' ', $ticket->status) }}</span></td>
                        <td>{{ $ticket->assignedUser->name ?? 'Unassigned' }}</td>
                        <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="color:#9ca3af; text-align:center; padding:16px;">No tickets found for this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div class="footer-left">HelpDesk System &mdash; Confidential Report</div>
        <div class="footer-right">Total: {{ $totalTickets }} tickets</div>
    </div>
</body>
</html>
