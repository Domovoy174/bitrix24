<?php

/*
    ver 1.0.0 
    date: 17.05.2024
    who changed it: Admin_2
*/

// Метод начинает новый рабочий день либо возобновляет закрытый или приостановленный.
function startDay($id)
{
    $result = CRest::call(
        'timeman.open',
        [
            'USER_ID' => $id
        ]
    );
    return $result;
}

// Метод возвращает информацию о текущем рабочем дне.
function statusDay($id)
{
    $result = CRest::call(
        'timeman.status',
        [
            'USER_ID' => $id
        ]
    );
    return $result;
}

// Метод возвращает настройки рабочего времени пользователя.
function settingDay($id)
{
    $result = CRest::call(
        'timeman.settings',
        [
            'USER_ID' => $id
        ]
    );
    return $result;
}
