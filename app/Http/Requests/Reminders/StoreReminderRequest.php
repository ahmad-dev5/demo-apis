<?php

namespace App\Http\Requests\Reminders;

use Illuminate\Foundation\Http\FormRequest;

class StoreReminderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
{
    return [
        'reminder_list_id' => 'required|exists:reminder_lists,id',
        'date' => 'required|date',
        'time' => 'required',
        'repeat_days' => 'sometimes|array',
        'repeat_days.*' => 'integer|between:1,7'
    ];
}

}
