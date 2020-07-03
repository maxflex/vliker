<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use App\Utils\Url;
use App\Enums\TaskType;

class StoreRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'url' => [
                'bail',
                'required',
                function ($attribute, $value, $fail) {
                    if (preg_match('#^http[s]?\:\/\/[m]?[\.]?vk.com#', $value) === 0) {
                        return $fail(trans('validation.task.wrong_host'));
                    }
                },
                // такого по идее никогда не должно быть
                function ($attribute, $value, $fail) {
                    if (!auth()->check()) {
                        return $fail(trans('validation.task.not_logged_in'));
                    }
                },
            ],
            'type' => ['enum_value:' . TaskType::class],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $canStart = true;

            // если URL предназначен для лайков
            if (Url::isLikeable($this->url)) {
                // ... а задача на подписки
                if ($this->type === TaskType::Subscribe) {
                    $canStart = false;
                }
            } else {
                // если URL предназначен для подписок,
                // а выбрана накрутка лайков или комментов
                if (in_array($this->type, [TaskType::Like, TaskType::Comment])) {
                    $canStart = false;
                }
            }

            if (!$canStart) {
                $validator->errors()->add('general', __('validation.task.cant-start-' . $this->type));
            }
        });
    }

    public function messages()
    {
        return [
            'required' => trans('validation.task.required'),
        ];
    }
}
