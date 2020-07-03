<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Есть ли у пользователя новые уведомления
 */
class NotificationsController extends Controller
{
    /**
     * Получить кол-во новых уведомлений
     */
    public function index(Request $request)
    {
        return [
            'notification_count' => auth()->user()->notification_count
        ];
    }

    /**
     * Отметить уведомления прочитанными
     */
    public function see()
    {
        $lastSeenCompletedTaskId = auth()->user()->completedTasksToMe()->max('completed_tasks.id');

        auth()->user()->last_seen_completed_task_id = $lastSeenCompletedTaskId;
        auth()->user()->save();
    }
}
