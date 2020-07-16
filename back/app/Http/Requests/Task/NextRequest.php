<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Task;

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
            'task_id_from' => [
                'required',
                'exists:tasks,id',
                // накручивать можно только на свои задачи
                function ($attribute, $value, $fail) {
                    $task = Task::find($value);
                    if ($task->user_id !== auth()->id()) {
                        $fail(__('validation.task.is-not-mine'));
                    }
                },
            ],

            'action_id' => [
                'nullable',
                'exists:actions,id',
                // нельзя лайкать свои задачи
                // function ($attribute, $value, $fail) {
                //     $task = Task::find($value);
                //     if ($task->user_id === auth()->id()) {
                //         $fail(__('validation.task.target-is-mine'));
                //     }
                // }
            ],
        ];
    }
}
