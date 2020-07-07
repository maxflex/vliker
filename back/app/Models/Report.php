<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    // допустимое кол-во жалоб. далее исключаем задачу
    const LIMIT = 5;

    protected $fillable = ['task_id'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
