<?php

use App\Enums\TaskType;

return [
    'task' => [
        'wrong_host' => 'cсылка должна быть с vk.com',
        'wrong_type' => 'неверный тип накрутки',
        'required' => 'пустая ссылка',
        'not_logged_in' => 'действие запрещено',
        'cant-start-' . TaskType::Subscribe => 'укажите ссылку на профиль или сообщество для подписки',
        'cant-start-' . TaskType::Like => 'укажите ссылку на фото или пост для накрутки лайков',
        'cant-start-' . TaskType::Comment => 'укажите ссылку на фото или пост для накрутки комментариев',
        'is-not-mine' => 'накручивать можно только на свои задачи',
        'target-is-mine' => 'нельзя лайкать свои задачи',
    ],
];
