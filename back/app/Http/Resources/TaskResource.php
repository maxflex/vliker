<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return extract_fields($this, [
            'id', 'url', 'type'
        ], [
            'actions_from_count' => $this->actionsFrom()->count(),
        ]);
    }
}
