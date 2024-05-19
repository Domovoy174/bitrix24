<?php

/*
    ver 1.0
    date: 07.05.2024
    who changed it: Admin_1
*/

function taskCreate($fields)
{
    $result = CRest::call(
        'tasks.task.add',
        [
            "fields" => $fields,
        ]
    );
    return $result;
}
