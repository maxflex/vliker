<?php

namespace App\Observers;

use App\Models\Action;
use App\Models\Task;

class ActionObserver
{
    public function created($action)
    {
        // TODO: разбанить задачу, если она забанена и продолжает накрутку
        // ей уже было показано соответствуюее уведомление на фронте
        // может, страница теперь открыта и доступна
        // if ($action->taskFrom->is_banned) {
        // }
    }

    /**
     * При бане удаляются все actions
     * Значит, если от удаленного action были какие-то дейсвия – аннулируем их
     */
    public function deleted($action)
    {
        Action::where('action_id', $action->id)->update([
            'action_id' => null
        ]);
    }
}
