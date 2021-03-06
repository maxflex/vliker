<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return extract_fields($this, [
            'id', 'new_notifications', 'api_token'
        ], [
            'has_tasks' => $this->tasks()->exists(),
        ]);
    }
}
