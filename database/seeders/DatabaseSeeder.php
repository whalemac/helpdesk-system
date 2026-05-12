<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── 1. DEFAULT ADMIN ─────────────────────────────────────────────
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // ── 2. SAMPLE AGENTS & SUPERVISOR ────────────────────────────────
        $agent1 = User::create([
            'name' => 'Alice Agent',
            'email' => 'alice@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
        ]);

        $agent2 = User::create([
            'name' => 'Bob Agent',
            'email' => 'bob@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'agent'
        ]);

        User::create([
            'name' => 'Sarah Supervisor',
            'email' => 'sarah@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
        ]);

        // ── 3. SAMPLE CATEGORIES ─────────────────────────────────────────
        $categories = [
            ['name' => 'Technical Support', 'description' => 'Hardware and software issues', 'status' => 'active'],
            ['name' => 'Account & Access', 'description' => 'Login, accounts, and permissions', 'status' => 'active'],
            ['name' => 'Network & Connectivity', 'description' => 'Internet and network problems', 'status' => 'active'],
            ['name' => 'Billing & Finance', 'description' => 'Billing inquiries and payment issues', 'status' => 'active'],
            ['name' => 'General Inquiry', 'description' => 'General questions and information', 'status' => 'active'],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insert(array_merge($cat, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $catIds = DB::table('categories')->pluck('id')->toArray();

        // ── 4. SAMPLE REQUESTERS ─────────────────────────────────────────
        $requesters = [
            ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john.doe@example.com', 'phone' => '09171234567', 'department' => 'IT Department'],
            ['first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane.smith@example.com', 'phone' => '09181234567', 'department' => 'Finance'],
            ['first_name' => 'Carlos', 'last_name' => 'Reyes', 'email' => 'carlos.r@example.com', 'phone' => '09191234567', 'department' => 'HR Department'],
            ['first_name' => 'Maria', 'last_name' => 'Santos', 'email' => 'maria.s@example.com', 'phone' => '09201234567', 'department' => 'Operations'],
        ];

        foreach ($requesters as $req) {
            DB::table('requesters')->insert(array_merge($req, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $reqIds = DB::table('requesters')->pluck('id')->toArray();

        // ── 5. SAMPLE TICKETS ─────────────────────────────────────────────
        $statuses = ['open', 'in_progress', 'pending', 'resolved', 'closed'];
        $priorities = ['low', 'medium', 'high', 'critical'];

        $tickets = [
            [
                'requester_id' => $reqIds[0],
                'category_id' => $catIds[0],
                'subject' => 'Cannot connect to company VPN',
                'description' => 'I am unable to connect to the company VPN from my home office. The connection times out after a few seconds.',
                'priority' => 'high',
                'status' => 'open',
                'assigned_user_id' => $agent1->id,
                'created_by' => $admin->id,
            ],
            [
                'requester_id' => $reqIds[1],
                'category_id' => $catIds[1],
                'subject' => 'Password reset request',
                'description' => 'I forgot my password and cannot log into the system. Please assist me with a password reset.',
                'priority' => 'medium',
                'status' => 'resolved',
                'assigned_user_id' => $agent2->id,
                'created_by' => $admin->id,
            ],
            [
                'requester_id' => $reqIds[2],
                'category_id' => $catIds[2],
                'subject' => 'Slow internet connection in Conference Room B',
                'description' => 'The internet in Conference Room B is extremely slow, especially during video calls. Affects all users in that room.',
                'priority' => 'medium',
                'status' => 'in_progress',
                'assigned_user_id' => $agent1->id,
                'created_by' => $admin->id,
            ],
            [
                'requester_id' => $reqIds[3],
                'category_id' => $catIds[3],
                'subject' => 'Incorrect invoice amount',
                'description' => 'The invoice for last month shows an incorrect amount. Please review and correct.',
                'priority' => 'high',
                'status' => 'pending',
                'assigned_user_id' => null,
                'created_by' => $admin->id,
            ],
            [
                'requester_id' => $reqIds[0],
                'category_id' => $catIds[4],
                'subject' => 'Request for a new monitor',
                'description' => 'I need an additional monitor for my workstation to improve productivity.',
                'priority' => 'low',
                'status' => 'closed',
                'assigned_user_id' => $agent2->id,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($tickets as $ticket) {
            DB::table('tickets')->insert(array_merge($ticket, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $ticketIds = DB::table('tickets')->pluck('id')->toArray();

        // ── 6. SAMPLE TICKET REPLIES ──────────────────────────────────────
        $replies = [
            [
                'ticket_id' => $ticketIds[0],
                'user_id' => $agent1->id,
                'message' => 'Hi John, I am looking into the VPN issue. Could you tell me which operating system you are using?',
                'reply_type' => 'reply',
            ],
            [
                'ticket_id' => $ticketIds[0],
                'user_id' => $admin->id,
                'message' => 'Ticket escalated to senior network team for further review.',
                'reply_type' => 'internal_note',
            ],
            [
                'ticket_id' => $ticketIds[1],
                'user_id' => $agent2->id,
                'message' => 'Password has been reset. Please check your email for your temporary password.',
                'reply_type' => 'reply',
            ],
            [
                'ticket_id' => $ticketIds[1],
                'user_id' => $agent2->id,
                'message' => 'Ticket resolved. User confirmed they can now log in.',
                'reply_type' => 'status_update',
            ],
            [
                'ticket_id' => $ticketIds[2],
                'user_id' => $agent1->id,
                'message' => 'Checked the router in Conference Room B. Firmware update in progress. Should be resolved within the hour.',
                'reply_type' => 'reply',
            ],
        ];

        foreach ($replies as $reply) {
            DB::table('ticket_replies')->insert(array_merge($reply, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
