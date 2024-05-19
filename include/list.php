<?php

/*
    ver 1.0.0
    date: 02.05.2024
    who changed it: Admin_2
*/

// Получение данных с
function listGetInfo($IBLOCK_TYPE_ID, $IBLOCK_CODE = null, $IBLOCK_ID = null, $SOCNET_GROUP_ID = null, $order = null)
{
    $result = CRest::call(
        'lists.get',
        [
            'IBLOCK_TYPE_ID' => $IBLOCK_TYPE_ID,
            'IBLOCK_CODE' => $IBLOCK_CODE,
            'IBLOCK_ID' => $IBLOCK_ID,
            "SOCNET_GROUP_ID" => $SOCNET_GROUP_ID,
            'IBLOCK_ORDER' => $order
        ]
    );
    return $result;
}

// Получение данных с
function listElementGetInfo($IBLOCK_TYPE_ID, $IBLOCK_CODE = null, $IBLOCK_ID = null, $SOCNET_GROUP_ID = null, $order = null, $ELEMENT_CODE = null, $ELEMENT_ID = null, $FILTER = null)
{
    $result = CRest::call(
        'lists.element.get',
        [
            'IBLOCK_TYPE_ID' => $IBLOCK_TYPE_ID,
            'IBLOCK_CODE' => $IBLOCK_CODE,
            'IBLOCK_ID' => $IBLOCK_ID,
            "SOCNET_GROUP_ID" => $SOCNET_GROUP_ID,
            'ELEMENT_CODE' => $ELEMENT_CODE,
            'ELEMENT_ID' => $ELEMENT_ID,
            'ELEMENT_ORDER' => $order,
            'FILTER' => $FILTER,
        ]
    );
    return $result;
}
