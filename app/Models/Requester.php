<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requester extends Model
{
    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'department'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
