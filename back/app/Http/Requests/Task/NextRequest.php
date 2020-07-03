<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Task\Task;

class NextRequest extends FormRequest
{
    // накручивать можно только на свои задачи
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'completed_by_task_id' => [
                'required',
                'exists:tasks,id',
                // накручивать можно только на свои задачи
                function($attribute, $value, $fail) {
                    $task = Task::find($value);
                    if ($task->user_id !== auth()->id()) {
                        $fail(__('validation.task.is-not-mine'));
                    }
                },
            ],

            'target_task_id' => [
                'nullable',
                'exists:tasks,id',
                'different:completed_by_task_id',
                // нельзя лайкать свои задачи
                function ($attribute, $value, $fail) {
                    $task = Task::find($value);
                    if ($task->user_id === auth()->id()) {
                        $fail(__('validation.task.target-is-mine'));
                    }
                }
            ],
        ];
    }
}
