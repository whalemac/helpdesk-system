<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'requester_id', 'category_id', 'subject', 'description', 
        'priority', 'status', 'assigned_user_id', 'created_by'
    ];

    public function requester() { return $this->belongsTo(Requester::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function assignedUser() { return $this->belongsTo(User::class, 'assigned_user_id'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function replies() { return $this->hasMany(TicketReply::class); }
}
