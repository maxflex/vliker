<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Model;

class ReportedTask extends Model
{
    // после этого задача считается забаненой
    static $limit = 3;

    protected $fillable = ['reported_task_id'];
}
