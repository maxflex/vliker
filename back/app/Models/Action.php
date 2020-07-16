<?php

namespace App\Models;

use App\Scopes\ActionScopes;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use ActionScopes;

    protected $fillable = [
        'task_id_from', 'task_id_to', 'action_id'
    ];

    public function taskFrom()
    {
        return $this->belongsTo(Task::class, 'task_id_from');
    }

    public function taskTo()
    {
        return $this->belongsTo(Task::class, 'task_id_to');
    }

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
