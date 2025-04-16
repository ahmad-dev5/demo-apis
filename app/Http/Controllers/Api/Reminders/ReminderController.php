<?php

namespace App\Http\Controllers\API\Reminders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reminders\StoreReminderRequest;
use App\Http\Requests\Reminders\UpdateReminderRequest;
use App\Models\Reminder;
use App\Services\Reminders\ReminderService;

class ReminderController extends Controller
{
    protected $reminderService;

    public function __construct(ReminderService $reminderService)
    {
        $this->reminderService = $reminderService;
    }

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => $this->reminderService->index()
        ]);
    }

    public function store(StoreReminderRequest $request)
    {
        return $this->reminderService->store($request->validated());
    }

    public function update(UpdateReminderRequest $request, Reminder $reminder)
    {
        return $this->reminderService->update($reminder, $request->validated());
    }

    public function destroy(Reminder $reminder)
    {
        return $this->reminderService->destroy($reminder);
    }
}

