<?php

/**
 * Извлекаем конкретные поля из объекта
 *
 * @param mixed $object
 * @param array $fields
 * @param array $additional
 */
function extract_fields($object, array $fields, array $additional = [])
{
    $result = [];

    foreach ($fields as $field) {
        $result[$field] = $object->{$field};
    }

    return array_merge($result, $additional);
}

function json_redecode($object)
{
    return json_decode(json_encode($object));
}

function cache_key(...$args)
{
    return  implode(':', array_merge([strtolower(config('app.name'))], $args));
}
