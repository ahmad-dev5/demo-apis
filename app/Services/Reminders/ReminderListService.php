<?php

namespace App\Services\Reminders;

use App\Models\ReminderList;
use App\Models\ActivityLog;
use App\Models\ErrorLog;

class ReminderListService
{
    public function index()
    {
        return ReminderList::all();
    }

    public function store(array $data)
    {
        try {
            $list = ReminderList::create($data);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Created Reminder List: ' . $list->name,
            ]);

            return response()->json(['success' => true, 'message' => 'Reminder list created', 'data' => $list]);
        } catch (\Exception $e) {
            ErrorLog::create([
                'action' => 'Store Reminder List',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json(['success' => false, 'message' => 'Reminder list creation failed', 'error' => $e->getMessage()]);
        }
    }

    public function update(ReminderList $reminderList, array $data)
    {
        try {
            $reminderList->update($data);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Updated Reminder List: ' . $reminderList->name,
            ]);

            return response()->json(['success' => true, 'message' => 'Reminder list updated', 'data' => $reminderList]);
        } catch (\Exception $e) {
            ErrorLog::create([
                'action' => 'Update Reminder List',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json(['success' => false, 'message' => 'Reminder list update failed', 'error' => $e->getMessage()]);
        }
    }

    public function destroy(ReminderList $reminderList)
    {
        try {
            $reminderList->delete();

            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Deleted Reminder List: ' . $reminderList->name,
            ]);

            return response()->json(['success' => true, 'message' => 'Reminder list deleted']);
        } catch (\Exception $e) {
            ErrorLog::create([
                'action' => 'Delete Reminder List',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json(['success' => false, 'message' => 'Reminder list deletion failed', 'error' => $e->getMessage()]);
        }
    }
}
