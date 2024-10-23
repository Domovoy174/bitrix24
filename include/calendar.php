<?php

// Возвращает список (массив) всех ресурсов.
function calendarResourceListGet()
{
    $result = CRest::call(
        'calendar.resource.list',
        []
    );
    return $result;
}


// Предоставляет возможность выбрать бронирования ресурсов.
function calendarResourceBookingListGet($filter)
{
    $result = CRest::call(
        'calendar.resource.booking.list',
        [
            "filter" => $filter,
        ]
    );
    return $result;
}

// Возвращает список событий календаря.
function calendarEventGet($type, $ownerId = null, $from = null, $to = null, $section = null)
{
    $result = CRest::call(
        'calendar.event.get',
        [
            "type" => $type,
            "ownerId" => $ownerId,
            "from" => $from,
            "to" => $to,
            "section" => $section
        ]
    );
    return $result;
}
// Возвращает список календарей. Здесь и в дальнейшем section будет именоваться как "календарь".
function calendarSectionGet($type, $ownerId)
{
    $result = CRest::call(
        'calendar.section.get',
        [
            "type" => $type,
            "ownerId" => $ownerId,
        ]
    );
    return $result;
}
// Метод возвращает событие календаря по идентификатору.
function calendarEventByIDGet($id)
{
    $result = CRest::call(
        'calendar.event.getbyid',
        [
            "id" => $id,
        ]
    );
    return $result;
}

// Редактирует существующее событие.
function calendarEventUpdate($id, $type, $ownerId, $section, $name)
{
    $result = CRest::call(
        'calendar.event.update',
        [
            "id" => $id,
            "type" => $type,
            "ownerId" => $ownerId,
            "section" => $section,
            "name" => $name,
        ]
    );
    return $result;
}
// Возвращает список будущих событий для текущего пользователя.
function calendarEventNearestGet($type, $ownerId, $days = 60, $forCurrentUser = true)
{
    $result = CRest::call(
        'calendar.event.get.nearest',
        [
            "type" => $type,
            "ownerId" => $ownerId,
            "days" => $days,
            "forCurrentUser" => $forCurrentUser,
        ]
    );
    return $result;
}
