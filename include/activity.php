<?php

/**
 *
 * @version 1.3.1
 * @date 29.05.2024
 * @who-changed-it Admin_2
 *
 */

//
function activityListGet($filter, $order = null, $select = null)
{
    $result = CRest::call(
        'crm.activity.list',
        [
            'order' => $order,
            'filter' => $filter,
            'select' => $select,
        ]
    );
    return $result;
}
