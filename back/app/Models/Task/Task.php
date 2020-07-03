<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Model;
use App\Utils\Url;

class Task extends Model
{
    use TaskScopes;

    // сколько максимально разрешено лайков
    // если больше, то задача считается забанной
    static $maxCompletedAllowed = 150;

    protected $fillable = ['url', 'type'];

    /**
     * Накручено тобой
     */
    public function completed()
    {
        return $this->hasMany(CompletedTask::class, 'completed_by_task_id');
    }

    /**
     * Поставлено тебе
     */
    public function received()
    {
        return $this->hasMany(CompletedTask::class, 'target_task_id');
    }

    /**
     * Оставлено жалоб
     */
    public function reports()
    {
        return $this->hasMany(TaskReport::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->url = Url::shorten($model->url);
        });
    }
}
