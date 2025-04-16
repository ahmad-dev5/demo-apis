<?php

namespace App\Models;

use App\Models\Reminder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReminderList extends Model
{
    protected $fillable = ['name'];

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }
}
