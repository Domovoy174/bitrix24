<?php

/*
    ver 1.0.0 
    date: 04.04.2024
    who changed it: Admin_2
*/


// Генерация случайной строки, можно задать количество символов через входную переменную
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}
