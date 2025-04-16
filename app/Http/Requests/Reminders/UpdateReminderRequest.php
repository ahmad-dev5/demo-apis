<?php

namespace App\Http\Requests\Reminders;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReminderRequest extends FormRequest
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
        'reminder_list_id' => 'sometimes|exists:reminder_lists,id',
        'date' => 'sometimes|date',
        'time' => 'sometimes',
        'repeat_days' => 'sometimes|array',
        'repeat_days.*' => 'integer|between:1,7'
    ];
}
}
