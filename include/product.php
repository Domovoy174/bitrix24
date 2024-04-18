<?php

// Создаем товар в элементе
function productSet($ownerId, $ownerType, $rows)
{
    $result = CRest::call(
        'crm.item.productrow.set',
        [
            'ownerId' =>  $ownerId,
            'ownerType' => $ownerType,
            'productRows' => $rows,
        ]
    );
    return $result;
}
