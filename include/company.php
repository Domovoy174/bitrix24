<?php

/*
    ver 1.0.0
    date: 04.04.2024
    who changed it: Admin_2
*/


// Получение данных компании
function companyGetInfo($companyID)
{
    $result = CRest::call(
        'crm.company.get',
        [
            'id' => $companyID,
        ]
    );
    return $result;
}


//Обновление компании
function companyUpdate($companyID, $fields)
{
    $result = CRest::call(
        'crm.company.update',
        [
            'id' => $companyID,
            'fields' => $fields,
        ]
    );
    return $result;
}
