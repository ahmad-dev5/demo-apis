<?php

namespace App\Models;

use App\Models\ReminderList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reminder extends Model
{
    protected $fillable = ['reminder_list_id', 'date', 'time', 'repeat_days', 'user_id'];

    protected $casts = [
        'repeat_days' => 'array',
    ];

    public function reminderList()
    {
        return $this->belongsTo(ReminderList::class);
    }
}

