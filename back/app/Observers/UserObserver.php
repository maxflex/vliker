<?php

namespace App\Observers;

use Illuminate\Support\Str;

class UserObserver
{
    public function creating($user)
    {
        $user->api_token = Str::random(80);
    }
}
