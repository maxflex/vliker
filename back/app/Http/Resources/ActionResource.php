<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActionResource extends JsonResource
{
    /**
     * Лайкаем мы Action, а не Task
     * Этот ресурс возвращается в /next
     */
    public function toArray($request)
    {
        return extract_fields($this, [
            'id'
        ], [
            'task' => [
                'id' => $this->taskFrom->id,
                'url' => $this->taskFrom->url,
            ],
        ]);
    }
}
