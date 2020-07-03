<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Task\{
    Task,
    CompletedTask,
    ReportedTask
};

class User extends Authenticatable
{
    protected $fillable = [
        'password',
    ];

    protected $hidden = [
        'password'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Задания, выполненные мной (текущим пользователем)
     */
    public function completedTasksByMe()
    {
        return $this->hasManyThrough(
            CompletedTask::class,
            Task::class,
            'user_id',
            'completed_by_task_id'
        );
    }

    /**
     * Задания, выполненные по отношению ко мне (по отношению к текущему пользователю)
     */
    public function completedTasksToMe()
    {
        return $this->hasManyThrough(
            CompletedTask::class,
            Task::class,
            'user_id',
            'target_task_id'
        );
    }

    public function reportedTasks()
    {
        return $this->hasMany(ReportedTask::class, 'reported_by_user_id');
    }

    /**
     * Кол-во новых уведомлений: храним ID последней выполненной задачи для пользователя,
     * notification_count = кол-во completed_tasks c момента сохраненного last_seen_completed_task_id
     */
    public function getNotificationCountAttribute()
    {
        $query = $this->completedTasksToMe();

        if ($this->last_seen_completed_task_id > 0) {
            $query->where('completed_tasks.id', '>', $this->last_seen_completed_task_id);
        }

        return $query->count();
    }

    public static function booted()
    {
        static::creating(fn ($user) => $user->api_token = Str::random(80));
    }
}
