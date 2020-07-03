<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Model;

class CompletedTask extends Model
{
    protected $fillable = ['completed_by_task_id', 'target_task_id'];
}
