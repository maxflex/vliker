<?php

use App\Enums\TaskType;
use App\Models\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    $urls = collect([
        'https://vk.com/tmaranina?z=photo13788597_456241331%2Falbum13788597_0%2Frev',
        'https://vk.com/id195816556?z=photo195816556_331512427%2Falbum195816556_0%2Frev',
        'https://vk.com/id312431407?z=photo312431407_456239581%2Falbum312431407_0%2Frev',
        'https://vk.com/genumalinka777?z=photo13226505_374553681%2Falbum13226505_0%2Frev',
        'https://vk.com/roma_acorn?z=photo11481439_456242014%2Falbum11481439_0%2Frev',
        'https://vk.com/roma_acorn?z=photo11481439_352120444%2Falbum11481439_0%2Frev',
        'https://vk.com/id4451?z=photo4451_438124211%2Falbum4451_0%2Frev',
        'https://vk.com/id28758010?z=photo28758010_456239023%2Falbum28758010_0%2Frev',
        'https://vk.com/zornov?z=photo138728600_456239462%2Falbum138728600_0%2Frev',
        'https://vk.com/idim7pulse?z=photo345683401_456239017%2Falbum345683401_0%2Frev',
        'https://vk.com/shil55?z=photo12875569_456240844%2Falbum12875569_0%2Frev',
        'https://vk.com/yarikker?z=photo107818367_456239807%2Falbum107818367_0%2Frev',
        'https://vk.com/nastaisha?z=photo17410965_456250292%2Falbum17410965_0%2Frev',
    ]);

    return [
        'url' => $urls->random(),
        // 'type' => $taskTypes->random(),
        'type' => TaskType::Like,
    ];
});
