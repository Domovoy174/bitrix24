<?php

/*
    ver 1.0.0 
    date: 04.04.2024
    who changed it: Admin_2
*/

// Метод возвращает идентификаторы типов сущностей CRM и смарт-процессов.
function ownerType()
{
    $result = CRest::call(
        'crm.enum.ownertype'
    );
    return $result;
}
