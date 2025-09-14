<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'user_id',
        'due_date',
        'time_tracked',
        'status',
        'priority'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
