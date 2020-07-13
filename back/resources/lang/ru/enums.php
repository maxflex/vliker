<?php

use App\Enums\{
    BanReason,
    TaskType,
};

return [
    TaskType::class => [
        TaskType::Like => 'Лайки',
        TaskType::Subscribe => 'Подписчики',
        TaskType::Comment => 'Комментарии',
    ],
    BanReason::class => [
        BanReason::Report => '',
        BanReason::Cheat => '',
    ],
];
