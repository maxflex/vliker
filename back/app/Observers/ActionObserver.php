<?php

namespace App\Observers;

class ActionObserver
{
    public function created($action)
    {
        // Нужно ли деактивировать задачу, которой поставили лайк?
        if ($action->taskTo->is_active && !$action->taskTo->isActive()) {
            $action->taskTo->update([
                'is_active' => false
            ]);
        }

        // Нужно ли активировать задачу, которая поставила лайк?
        if (!$action->taskFrom->is_active && $action->taskFrom->isActive()) {
            $action->taskFrom->update([
                'is_active' => true
            ]);
        }

        // TODO: разбанить задачу, если она забанена и продолжает накрутку
        // ей уже было показано соответствуюее уведомление на фронте
        // может, страница теперь открыта и доступна
        // if ($action->taskFrom->is_banned) {
        // }
    }
}
