<?php

/*
    ver 2.1.0
    date: 10.04.2024
    who changed it: Admin_2
*/

// halt - Определяет прерывать ли последовательность запросов в случае ошибки.
// cmd - Массив запросов стандартного вида


function sendLittleBatch($arr)
{
    $result = CRest::call(
        'batch',
        [
            'halt' => '0',
            'cmd' => $arr,
        ]
    )['result']['result'];
    return $result;
}


/**
 * The function `sendBigBatch` divides a given array into smaller batches, sends requests with a
 * specified delay, and aggregates the results into a final array.
 *
 * @param arr The `sendBigBatch` function you provided seems to be designed to handle a large batch of
 * requests by splitting them into smaller batches and sending them sequentially with a delay between
 * each batch.
 * @param requestDelay The `requestDelay` parameter in the `sendBigBatch` function represents the delay
 * in microseconds between each batch request. In the provided code, the default value for
 * `requestDelay` is set to `500000` microseconds, which is equivalent to half a second (0.5 seconds).
 *
 * @return The function `sendBigBatch` returns an array `` which contains the results of
 * sending a large batch of requests. The array may include results, errors, next pages, total results,
 * and timing information for each batch. Additionally, if any result contains more than 50 items, the
 * function will make additional requests to retrieve all items and add them to the final result array.
 */

function sendBigBatch($arr, $requestDelay = 500000)
{

    /*
    Пример arr

    $arr['deal_' . $deal_id] = 'crm.item.list?'.
    http_build_query(array(
        "entityTypeId" => 158,
        "filter" => [
            "parentId2" => $deal_id
        ]
    ));

*/


    // результирующий массив
    $res_full_arr = [];
    // временный массив элементов
    $temp_arr = [];
    // временный массив элементов
    $resTempArr = [];
    // временный массив результата каждого batch
    $res_arr = [];
    // количество элементов в batch
    $max_element = 45;
    // общее количество элементов в массиве
    $count_arr = count($arr);
    // количество отправляемых запросов
    $requests_numbers = ceil($count_arr /  $max_element);

    // if ($requests_numbers > 1) {
    if ($requests_numbers) {
        // если количество batch > 1 то делим запросы на несколько частей
        // начальный элемент вырезки из  массива
        $start_element = 0;
        // конечный элемент вырезки из  массива
        $end_element = $max_element;
        // копируем из массива часть элементов и отправляем по ним запрос
        for ($i = 0; $i < $requests_numbers; $i++) {
            // начальный элемент
            $start_element = floor($i *  $max_element);
            // копируем часть массива
            $temp_arr = array_slice($arr, $start_element, $end_element);
            // пересчёт до какого элемента копировать
            $end_element =  ($end_element > $count_arr) ? $end_element + $max_element : $count_arr;
            // задержка в 0,5 секунды
            usleep($requestDelay);
            // отправляем запрос

            $resTempArr = CRest::call(
                'batch',
                [
                    'halt' => '0',
                    'cmd' => $temp_arr,
                ]
            );

            // если есть result
            if (isset($resTempArr["result"])) {

                // проверяем количество элементов в result
                if (count($resTempArr["result"]["result"]) > 0) {
                    // если элементов больше 0 то перебираем и записываем в итоговый result
                    foreach ($resTempArr["result"]["result"] as $items => $item) {
                        $res_full_arr["result"]["result"][$items] = $item;
                    }
                } else {
                    // если массив пустой то просто приравниваем его значение для отображение в итоговом result
                    $res_full_arr["result"]["result"] = $resTempArr["result"]["result"];
                }

                if (count($resTempArr["result"]["result_error"]) > 0) {
                    // если элементов больше 0 то перебираем и записываем в итоговый result
                    foreach ($resTempArr["result"]["result_error"] as $items => $item) {
                        $res_full_arr["result"]["result_error"][$items] = $item;
                    }
                } else {
                    // если массив пустой то просто приравниваем его значение для отображение в итоговом result
                    $res_full_arr["result"]["result_error"] = $resTempArr["result"]["result_error"];
                }

                if (count($resTempArr["result"]["result_next"]) > 0) {
                    // если элементов больше 0 то перебираем и записываем в итоговый result
                    foreach ($resTempArr["result"]["result_next"] as $items => $item) {
                        $res_full_arr["result"]["result_next"][$items] = $item;
                    }
                } else {
                    // если массив пустой то просто приравниваем его значение для отображение в итоговом result
                    $res_full_arr["result"]["result_next"] = $resTempArr["result"]["result_next"];
                }

                if (count($resTempArr["result"]["result_time"]) > 0) {
                    // если элементов больше 0 то перебираем и записываем в итоговый result
                    foreach ($resTempArr["result"]["result_time"] as $items => $item) {
                        $res_full_arr["result"]["result_time"][$items] = $item;
                    }
                } else {
                    // если массив пустой то просто приравниваем его значение для отображение в итоговом result
                    $res_full_arr["result"]["result_time"] = $resTempArr["result"]["result_time"];
                }

                if (count($resTempArr["result"]["result_total"]) > 0) {
                    // если элементов больше 0 то перебираем и записываем в итоговый result
                    foreach ($resTempArr["result"]["result_total"] as $items => $item) {
                        $res_full_arr["result"]["result_total"][$items] = $item;
                    }
                } else {
                    // если массив пустой то просто приравниваем его значение для отображение в итоговом result
                    $res_full_arr["result"]["result_total"] = $resTempArr["result"]["result_total"];
                }
            }

            if (isset($resTempArr["time"])) {
                foreach ($resTempArr["time"] as $items => $item) {
                    $res_full_arr["time"][$items] = $item;
                }
            }

            // если в какой то из записей есть больше 50 элементов то перезапускаем по ним с добавлением в общий результат
            if (isset($res_full_arr["result"]["result_next"]) and count($res_full_arr["result"]["result_next"]) > 0) {
                // задержка в 0,5 секунды
                usleep($requestDelay);
                // перебираем все записи у которых есть поле next
                foreach ($res_full_arr["result"]["result_next"] as $itemNext => $next) {

                    // для нового запроса необходимо разбить существующий на составные части
                    // находим начало параметров в запросе
                    $posStartQuery = strpos($arr[$itemNext], "?");
                    // определяем метод для запросов
                    $requestString = mb_substr($arr[$itemNext], 0, $posStartQuery);
                    // остальной запрос
                    $secondString = mb_substr($arr[$itemNext], $posStartQuery + 1);

                    // Разбираем строку с запросом
                    parse_str($secondString, $output);
                    // если существует переменная next то будем выполнять цикл пока есть значения в этом поле
                    while (isset($next)) {
                        $output["start"] =  $next;
                        // задержка в 0,5 секунды
                        usleep($requestDelay);
                        // отправляем запрос с маркеров следующей выдачи
                        $temp = CRest::call(
                            $requestString,
                            $output
                        );
                        // если ответ пришёл то объединяем items с итоговым объектом
                        if ($temp["result"]) {
                            // перебираем item и добавляем в объект
                            foreach ($temp["result"]["items"] as $items) {
                                array_push($res_full_arr["result"]["result"][$itemNext]["items"], $items);
                            }
                        } else {
                            // если result не пришёл, то для выхода из цикла обнуляем переменную next
                            $next = null;
                        }
                        // если в ответе есть next то изменяем переменную иначе обнуляем
                        if (isset($temp["next"])) {
                            $next = $temp["next"];
                        } else {
                            $next = null;
                        }
                    }
                }
            }
            // создаём своё свойство к количеством отправленных batch
            $res_full_arr["page_batch_" . (string) $i] = true;
        }
    } else {
        // если  нет запросов
    }
    return $res_full_arr;
}
