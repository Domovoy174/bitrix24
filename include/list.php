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
function listElementGetInfo($IBLOCK_TYPE_ID, $IBLOCK_CODE = null, $IBLOCK_ID = null, $SOCNET_GROUP_ID = null, $order = null, $ELEMENT_CODE = null, $ELEMENT_ID = null, $FILTER = null, $max_request = 1000)
{
    $arr_result = [];
    $resultFirst = CRest::call(
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
    // echo "<br>";
    // echo "_____________  ONE   ________________";
    // echo "<br>";
    // echo "<pre>";
    // print_r($resultFirst);
    // echo "</pre>";
    // echo "<br>";
    // echo "--------------------------------------------------";
    // echo "<br>";
    // создаём общий массив для результата
    $arr_result = array_merge($arr_result, $resultFirst);
    // если существуют ещё листы
    if (isset($resultFirst["next"])) {
        // копируем ключ time в time_1
        $arr_result["time_1"] = $resultFirst["time"];
        // удаляем старый ключ time
        unset($arr_result["time"]);
        // счётчик номера запроса
        $count = (int)2;
        // переменная для запроса следующего листа
        $start = $resultFirst["next"];
        // делаем цикл пока существует ключ "next" и не превышен лимит запросов count
        //
        // echo "<br>";
        // echo "count " . $count;
        // echo "<br>";
        // echo "<br>";
        // echo "max_request " . $max_request;
        // echo "<br>";
        // echo "<br>";
        // echo "count < max_request   = " . $count < $max_request;
        // echo "<br>";
        //
        do {
            // пауза что бы не заблокировали запросы
            sleep(1);
            // делаем новый запрос с ключом start
            $resultNext = CRest::call(
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
                    'start' => $start,
                ]
            );
            // echo "<br>";
            // echo "_____________  resultNext   ________________";
            // echo "<br>";
            // echo "<pre>";
            // print_r($resultNext);
            // echo "</pre>";
            // echo "<br>";
            // echo "--------------------------------------------------";
            // echo "<br>";
            // если существуют ещё листы то задаём новые данные
            if (isset($resultNext["next"])) {
                $start = $resultNext["next"];
                $arr_result["next"] =  $resultNext["next"];
            } else {
                // удаляем ключ next
                unset($arr_result["next"]);
            }
            // добавляем новые данные в результирующий массив
            $arr_result["result"] = array_merge($arr_result["result"], $resultNext["result"]);
            // перебираем ключи и добавляем в результирующий массив (что бы  не потерять значения новый ключей)
            foreach ($resultNext as $key => $value) {
                if ($key !== 'next' and $key !== 'result' and $key !== 'total') {
                    $arr_result[$key . '_' .  $count] = $resultNext[$key];
                }
            }
            $count++;
        } while (isset($resultNext["next"]) and $count < $max_request);
    }

    return $arr_result;
}



// возвращает доступные типа полей для указанного списка
function listFieldsGetInfo($IBLOCK_TYPE_ID, $IBLOCK_CODE = null, $IBLOCK_ID = null, $SOCNET_GROUP_ID = null, $FIELD_ID = null)
{
    $result = CRest::call(
        'lists.field.type.get',
        [
            'IBLOCK_TYPE_ID' => $IBLOCK_TYPE_ID,
            'IBLOCK_CODE' => $IBLOCK_CODE,
            'IBLOCK_ID' => $IBLOCK_ID,
            "SOCNET_GROUP_ID" => $SOCNET_GROUP_ID,
            'FIELD_ID' => $FIELD_ID
        ]
    );
    return $result;
}


// Метод возвращает данные поля
function listDataFieldGetInfo($IBLOCK_TYPE_ID, $IBLOCK_CODE = null, $IBLOCK_ID = null, $SOCNET_GROUP_ID = null, $FIELD_ID = null)
{
    $result = CRest::call(
        'lists.field.get',
        [
            'IBLOCK_TYPE_ID' => $IBLOCK_TYPE_ID,
            'IBLOCK_CODE' => $IBLOCK_CODE,
            'IBLOCK_ID' => $IBLOCK_ID,
            "SOCNET_GROUP_ID" => $SOCNET_GROUP_ID,
            'FIELD_ID' => $FIELD_ID
        ]
    );
    return $result;
}


// Метод создаёт элемент списка. В случае успешного создания элемента ответ true, иначе Exception.
function listElementAdd($fields, $IBLOCK_TYPE_ID, $IBLOCK_ID, $ELEMENT_CODE, $LIST_ELEMENT_URL = null, $IBLOCK_CODE = null, $SOCNET_GROUP_ID = null)
{
    $result = CRest::call(
        'lists.element.add',
        [
            'IBLOCK_TYPE_ID' => $IBLOCK_TYPE_ID,
            'IBLOCK_ID' => $IBLOCK_ID,
            "IBLOCK_CODE" => $IBLOCK_CODE,
            'ELEMENT_CODE' => $ELEMENT_CODE,
            'LIST_ELEMENT_URL' => $LIST_ELEMENT_URL,
            "SOCNET_GROUP_ID" => $SOCNET_GROUP_ID,
            'FIELDS' => $fields,
        ]

    );
    return $result;
}

// Метод создаёт элемент списка. В случае успешного создания элемента ответ true, иначе Exception.
function listElementDelete($IBLOCK_TYPE_ID, $IBLOCK_ID, $ELEMENT_CODE, $ELEMENT_ID = null, $IBLOCK_CODE = null)
{
    $result = CRest::call(
        'lists.element.delete',
        [
            'IBLOCK_TYPE_ID' => $IBLOCK_TYPE_ID,
            'IBLOCK_ID' => $IBLOCK_ID,
            "IBLOCK_CODE" => $IBLOCK_CODE,
            'ELEMENT_CODE' => $ELEMENT_CODE,
            'ELEMENT_ID' => $ELEMENT_ID
        ]

    );
    return $result;
}

// Метод обновляет элемент списка. В случае успешного обновления элемента ответ true, иначе Exception.
function listElementUpdate($fields, $IBLOCK_TYPE_ID, $IBLOCK_ID, $ELEMENT_CODE, $IBLOCK_CODE = null, $SOCNET_GROUP_ID = null)
{
    $result = CRest::call(
        'lists.element.update',
        [
            'IBLOCK_TYPE_ID' => $IBLOCK_TYPE_ID,
            'IBLOCK_ID' => $IBLOCK_ID,
            "IBLOCK_CODE" => $IBLOCK_CODE,
            'ELEMENT_CODE' => $ELEMENT_CODE,
            "SOCNET_GROUP_ID" => $SOCNET_GROUP_ID,
            'FIELDS' => $fields,
        ]

    );
    return $result;
}


//  Метод возвращает id типа инфоблока. В случае успеха будет возвращена строка с id типа инфоблока, иначе null.
function listGetType($IBLOCK_ID)
{
    $result = CRest::call(
        'lists.get.iblock.type.id',
        [
            'IBLOCK_ID' => $IBLOCK_ID,
        ]

    );
    return $result;
}



//  Метод создает поле списка. В случае успешного создания поля ответ true, иначе Exception.
function listFieldAdd($params)
{
    $result = CRest::call(
        'lists.field.add',
        $params
    );
    return $result;
}

//  Метод обновляет поле списка. В случае успешного обновления поля ответ true, иначе Exception.
function listFieldUpdate($params)
{
    $result = CRest::call(
        'lists.field.update',
        $params
    );
    return $result;
}

//  Метод удаляет поле списка. В случае успешного удаления поля ответ true, иначе Exception.
function listFieldDelete($params)
{
    $result = CRest::call(
        'lists.field.delete',
        $params
    );
    return $result;
}




// функции для извлечения


// функция для извлечения текста из переменной битрикс типа HTML
function list_extract_data_html($raw)
{
    $result = null;
    if (isset($raw)) {
        // так как внутри один элемент но у него неизвестное имя и нет номера то перебираем его
        foreach ($raw as $key => $value) {
            $result = $value["TEXT"];
        }
    }
    return $result;
}

// функция для извлечения текста из переменной битрикс типа строка
function list_extract_data_string($raw)
{
    $result = null;
    if (isset($raw)) {
        // так как внутри один элемент но у него неизвестное имя и нет номера то перебираем его
        foreach ($raw as $key => $value) {
            $result = $value;
        }
    }
    return $result;
}
// функция для извлечения массива из переменной битрикс типа множественное
function list_extract_arr_string($raw)
{
    $result = [];
    if (isset($raw)) {
        // так как внутри один элемент но у него неизвестное имя и нет номера то перебираем его
        foreach ($raw as $key => $value) {
            array_push($result, $value);
        }
    }
    return $result;
}
