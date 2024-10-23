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


// Возвращает описание полей сделки, в том числе пользовательских.
function dealFieldsGet()
{
    $result = CRest::call(
        'crm.deal.fields',
        []
    );
    return $result;
}
// Возвращает пользовательское поле сделок по идентификатору.
function dealFieldGet($dealID)
{
    $result = CRest::call(
        'crm.deal.userfield.get',
        [
            'id' => $dealID,
        ]
    );
    return $result;
}


function dealGetFullListFilter($filterDeal, $selectDeal)
{
    // итоговый объект
    $arrFull = [];
    // количество страниц
    $numberPage = 0;
    // маркер для следующей записи
    $next = null;
    // отправляем первый запрос
    $temp = CRest::call(
        'crm.deal.list',
        [
            'filter' => $filterDeal,
            'select' => $selectDeal,
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
            'crm.deal.list',
            [
                'filter' => $filterDeal,
                'select' => $selectDeal,
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
