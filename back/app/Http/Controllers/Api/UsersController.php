<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Текущий авторизованный профиль пользователя
     */
    public function index()
    {
        // dd(auth()->getCookieJar());
        // return auth()->user();
        if (auth('api')->check()) {
            return new UserResource(auth('api')->user());
        }
        return response(null, 401);
    }

    public function store(Request $request)
    {
        // TODO: обязательно тут добавить Google RECaptcha
        // login: guest-будущий_ID
        // а нахуй password нужен? для будущих пользователей что ли?
        // или чтоб нельзя было залогиниться просто подменой куки?
        $user = User::create([
            // 'login' => 'guest-' . (User::max('id') + 1),
            'password' => Hash::make(uniqid()),
        ]);

        return new UserResource($user);
    }
}
