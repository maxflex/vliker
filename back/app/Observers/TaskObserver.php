<?php

namespace App\Observers;

use App\Utils\Url;

class TaskObserver
{
    public function creating($task)
    {
        $task->url = Url::shorten($task->url);
    }
}
