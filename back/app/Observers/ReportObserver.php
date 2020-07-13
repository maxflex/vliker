<?php

namespace App\Observers;

use App\Enums\BanReason;
use App\Models\Report;

class ReportObserver
{
    public function created($report)
    {
        // Бан задачи при превышении лимита по репортам
        if ($report->task->reports()->count() >= Report::LIMIT) {
            $report->task->ban(BanReason::Report);
        }
    }
}
