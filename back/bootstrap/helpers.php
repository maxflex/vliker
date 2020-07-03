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
