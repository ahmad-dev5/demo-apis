<?php

namespace App\Http\Controllers\API\Reminders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reminders\StoreReminderListRequest;
use App\Http\Requests\Reminders\UpdateReminderListRequest;
use App\Models\ReminderList;
use App\Services\Reminders\ReminderListService;

class ReminderListController extends Controller
{
    protected $reminderListService;

    public function __construct(ReminderListService $reminderListService)
    {
        $this->reminderListService = $reminderListService;
    }

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => $this->reminderListService->index()
        ]);
    }

    public function store(StoreReminderListRequest $request)
    {
        return $this->reminderListService->store($request->validated());
    }

    public function update(UpdateReminderListRequest $request, ReminderList $reminderList)
    {
        return $this->reminderListService->update($reminderList, $request->validated());
    }

    public function destroy(ReminderList $reminderList)
    {
        return $this->reminderListService->destroy($reminderList);
    }
}
