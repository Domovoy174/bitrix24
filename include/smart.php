<?php

/*
    ver 1.2.0
    date: 15.04.2024
    who changed it: Admin_2
*/
// Отдает информацию о полях смарт-процесса с идентификатором entityTypeId
function smartFieldsGetinfo($entityTypeId)
{
    $result = CRest::call(
        'crm.item.fields',
        [
            "entityTypeId" => $entityTypeId
        ]
    );
    return $result;
}



function smartGetinfo($entityTypeId, $smart_item_id)
{
    $result = CRest::call(
        'crm.item.get',
        [
            "entityTypeId" => $entityTypeId,
            "id" => $smart_item_id
        ]
    );
    return $result;
}


function smartGetListFilter($entityTypeId, $filter)
{
    $result = CRest::call(
        'crm.item.list',
        [
            "entityTypeId" => $entityTypeId,
            "filter" => $filter
        ]
    );
    return $result;
}


function smartGetFullListFilter($entityTypeId, $filter, $order = null)
{
    // итоговый объект
    $arrFull = [];
    // количество страниц
    $numberPage = 0;
    // маркер для следующей записи
    $next = null;
    // отправляем первый запрос
    $temp = CRest::call(
        'crm.item.list',
        [
            "entityTypeId" => $entityTypeId,
            "filter" => $filter,
            "order" => $order,
        ]
    );

    // если в ответе есть поле next значит есть ещё страницы
    if (isset($temp["next"])) {
        $next = $temp["next"];
        $temp["page_0_start_element"] =  "0";
        $numberPage++;
    }

    // объединяем итоговый и временный массивы
    $arrFull = array_merge($arrFull, $temp);

    // если существует переменная next то будем выполнять цикл пока есть значения в этом поле
    while (isset($next)) {

        $arrFull["page_" . $numberPage . "_start_element"] =  $next;
        // задержка в 0,5 секунды
        usleep(500000);
        // отправляем запрос с маркеров следующей выдачи
        $temp = CRest::call(
            'crm.item.list',
            [
                "entityTypeId" => $entityTypeId,
                "filter" => $filter,
                "order" => $order,
                "start" => $next
            ]
        );

        // если ответ пришёл то объединяем items с итоговым объектом
        if ($temp["result"]) {
            // перебираем item и добавляем в объект
            foreach ($temp["result"]["items"] as $items) {
                array_push($arrFull["result"]["items"], $items);
            }
        } else {
            // если result не пришёл, то для выхода из цикла обнуляем переменную next
            $next = null;
        }

        // если в ответе есть next то изменяем переменную иначе обнуляем
        if (isset($temp["next"])) {
            $next = $temp["next"];
            $numberPage++;
        } else {
            $next = null;
        }

        $arrFull["next"] =  $next;
    }

    //удалить next из конечного массива
    unset($arrFull["next"]);

    return $arrFull;
}


function smartUpgrade($entityTypeId, $smartElementId, $fields)
{
    $result = CRest::call(
        'crm.item.update',
        [
            "entityTypeId" => $entityTypeId,
            "id" => $smartElementId,
            'fields' => $fields,
        ]
    );
    return $result;
}

function smart_NO_Upgrade($entityTypeId, $smartElementId, $fields)
{
    $result = CRest::call(
        'crm.item.update',
        [
            "entityTypeId" => $entityTypeId,
            "id" => $smartElementId,
            'fields' => $fields,
            'params' => [
                'REGISTER_SONET_EVENT' => 'N'
            ]
        ]
    );
    return $result;
}


function smartAdd($entityTypeId, $fields)
{
    $result = CRest::call(
        'crm.item.add',
        [
            "entityTypeId" => $entityTypeId,
            'fields' => $fields,
        ]
    );
    return $result;
}


/*
    пример фильтра с логикой в смартах

    $filter = [
        "ufCrm_65F997A12E6CB" => $partner,
        "stageId" =>  'DT31_8:P',
        [
            'logic' => 'AND',
            [
                [
                    ">=begindate" => $begindate,
                ],
                [
                    "<=closedate" => $closedate
                ],
            ],
        ]
    ];

*/
