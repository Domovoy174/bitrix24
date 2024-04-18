<?php

/*
    ver 1.0.0 
    date: 04.04.2024
    who changed it: Admin_2
*/

// Получение данных сделки
function dealGetInfo($dealID)
{
    $result = CRest::call(
        'crm.deal.get',
        [
            'id' => $dealID,
        ]
    );
    return $result;
}


//Обновление сделки
function dealUpdate($dealID, $fields)
{
    $result = CRest::call(
        'crm.deal.update',
        [
            'id' => $dealID,
            'fields' => $fields,
        ]
    );
    return $result;
}


//Получение списка сделок по фильтру
function dealGetList($filterDeal, $selectDeal)
{
    $result = CRest::call(
        'crm.deal.list',
        [
            'filter' => $filterDeal,
            'select' => $selectDeal,
        ]
    );
    return $result;
}
