<?php

namespace App\Services\Reminders;

use App\Models\Reminder;
use App\Models\ActivityLog;
use App\Models\ErrorLog;

class ReminderService
{
    public function index()
    {
        return Reminder::with('reminderList')->where('user_id', auth()->id())->get();
    }

    public function store(array $data)
{
    try {
        $data['user_id'] = auth()->id();
        $data['repeat_days'] = $this->getDayNameByIds($data['repeat_days']); // Convert numeric days to names

        $reminder = Reminder::create([
            'reminder_list_id' => $data['reminder_list_id'],
            'user_id' => $data['user_id'],
            'date' => $data['date'],
            'time' => $data['time'],
            'repeat_days' => json_encode($data['repeat_days'])
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Created Reminder',
        ]);

        return response()->json(['success' => true, 'data' => $reminder], 201);
    } catch (\Exception $e) {
        ErrorLog::create([
            'action' => 'Failed to create reminder',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
        ]);

        return response()->json(['success' => false, 'message' => 'Failed to create reminder', 'error' => $e->getMessage()], 500);
    }
}

public function update(Reminder $reminder, array $data)
{
    try {
        if ($reminder->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized to update this reminder'], 403);
        }
        if (isset($data['repeat_days'])) {
            $data['repeat_days'] = $this->getDayNameByIds($data['repeat_days']);
            $data['repeat_days'] = json_encode($data['repeat_days']);
        }

        $reminder->update($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Updated Reminder',
        ]);

        return response()->json(['success' => true, 'data' => $reminder]);
    } catch (\Exception $e) {
        ErrorLog::create([
            'action' => 'Failed to update reminder',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
        ]);

        return response()->json(['success' => false, 'message' => 'Failed to update reminder'], 500);
    }
}


    public function destroy(Reminder $reminder)
    {
        try {
            if ($reminder->user_id !== auth()->id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized to delete this reminder'], 403);
            }
            $reminder->delete();

            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Deleted Reminder ID: ' . $reminder->id,
            ]);

            return response()->json(['success' => true, 'message' => 'Reminder deleted']);
        } catch (\Exception $e) {
            ErrorLog::create([
                'action' => 'Delete Reminder',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json(['success' => false, 'message' => 'Reminder deletion failed', 'error' => $e->getMessage()]);
        }
    }

    private function getDayNameByIds(array $dayIds): array
{
    $map = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday'
    ];

    $names = [];
    foreach ($dayIds as $id) {
        if (isset($map[$id])) {
            $names[] = $map[$id];
        }
    }

    return $names;
}

}
