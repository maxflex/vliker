<?php

use App\Enums\TaskType;

return [
    // Кол-во допустимых нарушений
    'limit' => 3,

    // Бан если запросы на /next поступают чаще чем раз в N секунд
    'seconds' => [
        TaskType::Like => 3,
        TaskType::Subscribe => 3,
        TaskType::Comment => 5,
    ],
];
