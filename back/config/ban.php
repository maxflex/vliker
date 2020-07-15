<?php

use App\Enums\TaskType;

return [
    // Кол-во допустимых нарушений
    'limit' => env('BAN_LIMIT'),

    // Бан если запросы на /next поступают чаще чем раз в N секунд
    'seconds' => [
        TaskType::Like => env('BAN_SECONDS_LIKE'),
        TaskType::Subscribe => env('BAN_SECONDS_SUBSCRIBE'),
        TaskType::Comment => env('BAN_SECONDS_COMMENT'),
    ],
];
