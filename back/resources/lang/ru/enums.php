<?php

use App\Enums\{
    TaskType
};

return [
    TaskType::class => [
        TaskType::Like => 'Лайки',
        TaskType::Subscribe => 'Подписчики',
        TaskType::Comment => 'Комментарии',
    ],
];
