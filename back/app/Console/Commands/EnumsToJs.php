<?php

namespace App\Console\Commands;

use BenSampo\Enum\Contracts\LocalizedEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class EnumsToJs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enums-to-js';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export enums to JS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $folder = '../front/src/enums/';
        $flags = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_FORCE_OBJECT;

        foreach (scandir('app/Enums') as $enum) {
            if ($enum === '.' or $enum === '..') {
                continue;
            }
            $classname = 'App\\Enums\\' . pathinfo($enum, PATHINFO_FILENAME);

            $class = new $classname($classname::getValues()[0]);
            if (!($class instanceof LocalizedEnum)) {
                continue;
            };
            $classBaseName = Str::upper(Str::snake(class_basename($classname)));
            $toArray = [];
            foreach ($classname::toArray() as $key => $value) {
                $toArray[Str::camel($key)] = $value;
            }

            $toArray = json_encode($toArray, $flags);
            $toSelectArray = json_encode($classname::toSelectArray(), $flags);
            $content = <<<JS
            export const {$classBaseName} = {$toArray}

            export const {$classBaseName}_TITLE = {$toSelectArray}

            export default {
                title: {$classBaseName}_TITLE,
                option: {$classBaseName},
                values: Object.values({$classBaseName}),
            }
            JS;
            file_put_contents($folder . Str::snake(class_basename($classname), '-') . '.js', $content);
            echo $classname . PHP_EOL;
        }
    }
}
