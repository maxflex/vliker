<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return extract_fields($this, [
            'id', 'url', 'type', 'is_active', 'is_banned'
        ], [
            'actions_from_count' => $this->actionsFrom()->count(),
            'actions_to_count' => $this->actionsTo()->count(),
        ]);
    }
}
