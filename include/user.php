<?php

/*
    ver 1.0.0
    date: 26.06.2024
    who changed it: Admin_2
*/


// Получение данных контакта
function userGetInfo($ID)
{
    $result = CRest::call(
        'user.get',
        [
            'id' => $ID,
        ]
    );
    return $result;
}
