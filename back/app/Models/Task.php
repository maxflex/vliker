<?php

namespace App\Models;

use App\Scopes\TaskScopes;
use App\Utils\Url;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use TaskScopes;

    protected $fillable = [
        'task_id_from', 'task_id_to', 'type', 'is_active', 'is_banned', 'url'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_banned' => 'boolean',
    ];

    /**
     * Поставлено задачей (я поставил)
     */
    public function actionsFrom()
    {
        return $this->hasMany(Action::class, 'task_id_from');
    }

    /**
     * Поставлено задаче (мне поставили)
     */
    public function actionsTo()
    {
        return $this->hasMany(Action::class, 'task_id_to');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Проверить, является ли задача активной
     * Когда поставленных задачей лайков больше, чем накрученных ей
     */
    public function isActive()
    {
        return $this->actionsFrom->count() > $this->actionsTo->count();
    }

    public function getUrlAttribute($value)
    {
        return Url::vk($value);
    }
}
