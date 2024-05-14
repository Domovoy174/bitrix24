<?php

/*
    ver 1.2.0
    date: 02.05.2024
    who changed it: Admin_2
*/


// преобразовать и  сохранить в виде json
function saveToLogJson($input)
{
    $date_current = date('d-m-Y H:i:s');
    $data = json_encode($input, true);
    $save =  file_put_contents('logs/' . $date_current . '_' . '.json', print_r($data, 1));
    return $save;
}


// преобразовать и  сохранить в виде json
function saveToLogTitleJson($input, $title)
{
    $date_current = date('d-m-Y H:i:s');
    $data = json_encode($input, true);
    $save =  file_put_contents('logs/' . $title  . '__' . $date_current . '_' . '.json', print_r($data, 1));
    return $save;
}


// сохранить в виде текста
function saveToLogTxt($input)
{
    $date_current = date('d-m-Y H:i:s');
    $save =  file_put_contents('logs/' . $date_current . '_' . '.txt', print_r($input, 1));
    return $save;
}


// сохранить в файл с ошибками
function saveLogAppendTxt($input)
{
    $date_current = date('d-m-Y H:i:s');
    $title =  PHP_EOL
        . '__________   '
        . $date_current
        . '   __________'
        . PHP_EOL
        . PHP_EOL;
    $save =  file_put_contents('logs/' . 'general_log' . '.txt', print_r($title, 1) . PHP_EOL, FILE_APPEND);
    $save =  file_put_contents('logs/' . 'general_log' . '.txt', print_r($input, 1) . PHP_EOL, FILE_APPEND);
    return $save;
}
// сохранить в файл с ошибками
function saveERRORToLogTxt($input)
{
    $date_current = date('d-m-Y H:i:s');
    $title =  PHP_EOL
        . '__________   '
        . $date_current
        . '   __________'
        . PHP_EOL
        . PHP_EOL;
    $save =  file_put_contents('logs/' . 'error' . '.txt', print_r($title, 1) . PHP_EOL, FILE_APPEND);
    $save =  file_put_contents('logs/' . 'error' . '.txt', print_r($input, 1) . PHP_EOL, FILE_APPEND);
    return $save;
}
