<?php

/*
    ver 1.0
    date: 07.05.2024
    who changed it: Admin_1
*/

use Google\Service\Sheets\Slicer;

function taskCreate($fields)
{
    $result = CRest::call(
        'tasks.task.add',
        [
            "fields" => $fields,
        ]
    );
    return $result;
}

// Возвращает информацию о конкретной задаче.
function tasksTaskGet($taskId, $select = null)
{
    $result = CRest::call(
        'tasks.task.get',
        [
            "taskId" => $taskId,
            "select" => $select
        ]
    );
    return $result;
}

// Метод обновляет задачу.
function tasksTaskUpdate($taskId, $fields)
{
    $result = CRest::call(
        'tasks.task.update',
        [
            "taskId" => $taskId,
            "fields" => $fields
        ]
    );
    return $result;
}

// Метод возвращает все доступные поля.
function tasksTaskGetFields()
{
    $result = CRest::call(
        'tasks.task.getFields',
        []
    );
    return $result;
}

// Метод возвращает все доступные поля.
function tasksTaskGetLists($filter, $select, $start = 0, $order = null)
{

    // итоговый массив
    $result_full = [
        "result" => [
            "tasks" => [],
        ],
        "full_time" => [],
        "total" => [],
        // "sleep" => [],
        // "last_operating_reset_at" => (int)0,
        // "requestDelay" => (int)0,
    ];


    $result = CRest::call(
        'tasks.task.list',
        [
            'filter' => $filter,
            'select' => $select,
            'order' => $order,
            'start' => $start,
        ]
    );

    $result_full["result"]["tasks"] = array_merge($result_full["result"]["tasks"], $result["result"]["tasks"]);
    $result_full["full_time"]["time_1"] = $result["time"];
    // если есть ещё элементы списка
    if (!empty($result["next"])) {

        $result_full["total"] = $result["total"];
        $next = $result["next"];
        // количество  запросов
        $i = 1;
        while (!empty($next)) {
            $i++;
            // пауза между запросами
            sleep(1);
            $resultNext = CRest::call(
                'tasks.task.list',
                [
                    'filter' => $filter,
                    'select' => $select,
                    'order' => $order,
                    'start' => $next,
                ]
            );
            // если есть next в ответе
            if (!empty($resultNext["next"])) {
                $next = $resultNext["next"];
            } else {
                // очищаем next для выхода из цикла
                $next = null;
            }
            // добавляем данные в результирующий массив
            $result_full["result"]["tasks"] = array_merge($result_full["result"]["tasks"], $resultNext["result"]["tasks"]);
            $result_full["full_time"]["time_" . $i] = $resultNext["time"];
        }
        return $result_full;
    } else {
        return $result;
    }
}
