<?php

/*
    ver 1.0.0 
    date: 02.05.2024
    who changed it: Admin_2
*/


function getTimeLineComment($id)
{
    $result = CRest::call(
        'crm.timeline.comment.get',
        [
            'id' => $id
        ]
    );

    return $result;
}

function updateTimeLineComment($id, $fields)
{
    $result = CRest::call(
        'crm.timeline.comment.update',
        [
            'id' => $id,
            "fields" => $fields
        ]
    );

    return $result;
}

//TODO результат постраничный, необходимо выбрать все страницы
function getTimeLineListComments($entityID, $entityType, $select = null, $order = null)
{

    /* пример
    $result = CRest::call(
    'crm.timeline.comment.list',
    [
        'filter' => [
            "ENTITY_ID" => 8031,
            "ENTITY_TYPE" =>  "deal",
        ],
        "select" =>
        [
            "ID",
            "COMMENT"
        ],
        "order" => [
            "CREATED" => 'DESC'
        ],
    ]
);

*/

    $result = CRest::call(
        'crm.timeline.comment.list',
        [
            'filter' => [
                "ENTITY_ID" => $entityID,
                "ENTITY_TYPE" =>  $entityType,
            ],
            "select" => $select,
            "order" => $order,
        ]
    );

    return $result;
}
