<?php

namespace App\Models;

use App\Enums\BanReason;
use App\Scopes\TaskScopes;
use App\Utils\Url;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    use TaskScopes;

    protected $fillable = [
        'task_id_from', 'task_id_to', 'type', 'is_active', 'url'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'is_banned'
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

    public function ban(string $banReason)
    {
        if (BanReason::hasValue($banReason)) {
            if ($banReason === BanReason::Cheat) {
                // Archive all actions
                DB::table("_actions")->insert(
                    $this->actionsFrom()->get()->map(fn ($item) => $item->toArray())->all()
                );
                // Remove all actions
                $this->actionsFrom()->delete();
            }
            $this->ban_reason = $banReason;
            return $this->save();
        }
        throw new Exception("Wrong ban reason");
    }

    public function getUrlAttribute($value)
    {
        return Url::vk($value);
    }

    public function setUrlAttribute($value)
    {
        $this->attributes['url'] = Url::shorten($value);
    }

    public function getIsBannedAttribute()
    {
        return $this->ban_reason !== null;
    }
}
