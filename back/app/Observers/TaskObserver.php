<?php

namespace App\Observers;

use App\Enums\BanReason;
use App\Utils\Url;
use Illuminate\Support\Facades\DB;

class TaskObserver
{
    public function creating($task)
    {
        $task->url = Url::shorten($task->url);
    }
}
