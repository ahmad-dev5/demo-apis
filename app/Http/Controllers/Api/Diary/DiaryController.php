<?php

namespace App\Http\Controllers\Api\Diary;

use App\Models\Diary;
use App\Http\Controllers\Controller;
use App\Services\Diary\DiaryService;
use App\Http\Requests\Diary\StoreDiaryRequest;
use App\Http\Requests\Diary\UpdateDiaryRequest;

class DiaryController extends Controller
{
    protected $diaryService;

    public function __construct(DiaryService $diaryService)
    {
        $this->diaryService = $diaryService;
    }

    public function store(StoreDiaryRequest $request)
    {
        return $this->diaryService->store($request->validated());
    }

    public function index()
    {
        return $this->diaryService->index();
    }

    public function show($id)
    {
        return $this->diaryService->show($id);
    }

    public function update(UpdateDiaryRequest $request, Diary $diary)
    {
        return $this->diaryService->update($diary, $request->validated());
    }

    public function destroy($id)
    {
        return $this->diaryService->destroy($id);
    }
}
