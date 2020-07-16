<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatsTaskResource extends JsonResource
{
    public function toArray($request)
    {
        return extract_fields($this, [
            'id', 'url', 'type', 'ban_reason', 'is_banned', 'latest_action_created_at'
        ], [
            'actions_from_count' => $this->actionsFrom()->count(),
            'actions_to_count' => $this->actionsTo()->count(),
        ]);
    }
}
