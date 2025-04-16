<?php

namespace App\Services\Diary;

use App\Models\Diary;
use App\Models\ErrorLog;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class DiaryService
{
    public function store(array $data)
    {
        try {
            $data['user_id'] = Auth::id();
            $diary = Diary::create($data);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity' => 'Diary created: ' . $diary->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Diary created successfully',
                'data' => $diary
            ]);
        } catch (\Exception $e) {
            ErrorLog::create([
                'action' => 'Store Diary',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create diary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $diaries = Diary::where('user_id', Auth::id())->get();

            return response()->json([
                'success' => true,
                'data' => $diaries
            ]);
        } catch (\Exception $e) {
            ErrorLog::create([
                'action' => 'Fetch Diaries',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch diaries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $diary = Diary::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $diary
            ]);
        } catch (\Exception $e) {
            ErrorLog::create([
                'action' => 'Show Diary',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Diary not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Diary $diary, array $data)
    {
        try {
            $diary->diary_name = $data['diary_name'] ?? $diary->diary_name;
            $diary->date = $data['date'] ?? $diary->date;
            $diary->time = $data['time'] ?? $diary->time;
            $diary->notes = $data['notes'] ?? $diary->notes;
            $diary->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity' => 'Diary updated: ' . $diary->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Diary updated successfully',
                'data' => $diary
            ]);
        } catch (\Exception $e) {
            ErrorLog::create([
                'action' => 'Update Diary',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Diary update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $diary = Diary::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $diary->delete();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity' => 'Diary deleted: ' . $id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Diary deleted successfully'
            ]);
        } catch (\Exception $e) {
            ErrorLog::create([
                'action' => 'Delete Diary',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Diary deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
