<?php

/*
    ver 1.0.0 
    date: 04.04.2024
    who changed it: Admin_2
*/


// Получение данных контакта
function contactGetInfo($contactID)
{
    $result = CRest::call(
        'crm.contact.get',
        [
            'id' => $contactID,
        ]
    );
    return $result;
}


//Обновление контакта
function contactUpdate($contactID, $fields)
{
    $result = CRest::call(
        'crm.contact.update',
        [
            'id' => $contactID,
            'fields' => $fields,
        ]
    );
    return $result;
}
