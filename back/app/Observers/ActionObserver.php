<?php

namespace App\Observers;

use App\Models\Task;

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
        $this->activateTask($action->taskFrom);

        // TODO: разбанить задачу, если она забанена и продолжает накрутку
        // ей уже было показано соответствуюее уведомление на фронте
        // может, страница теперь открыта и доступна
        // if ($action->taskFrom->is_banned) {
        // }
    }

    /**
     * При бане задачи за читы стираются actions
     * Может возникнуть такое, что задача была выполнена и деактивирована,
     * а после удаления action она опять должна стать активной,
     * т.к. удалили поставленный ей лайк
     */
    public function deleted($action)
    {
        $this->activateTask($action->taskTo);
    }

    private function activateTask(Task $task)
    {
        if (!$task->is_active && $task->isActive()) {
            $task->update([
                'is_active' => true
            ]);
        }
    }
}
