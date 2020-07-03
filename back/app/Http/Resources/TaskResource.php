<?php

namespace App\Http\Resources;

use App\Utils\Url;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'url' => Url::vk($this->url),
            'type' => $this->type,
            'received' => $this->received()->count(),
            'completed' => $this->completed()->count(),
        ];
    }
}
