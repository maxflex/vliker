<?php

namespace App\Observers;

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
}
