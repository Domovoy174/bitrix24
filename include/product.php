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

// Отдает информацию о товарной позиции с идентификатором id.
function productGet($id)
{
    $result = CRest::call(
        'crm.item.productrow.get',
        [
            'id' =>  $id
        ]
    );
    return $result;
}

// Отдает информацию о полях товарных позиций. Названия полей для входных данных следует брать из этого метода.
function productFieldsGet()
{
    $result = CRest::call(
        'crm.item.productrow.fields',
        []
    );
    return $result;
}

// Получает список товаров, по которым ещё не была выставлена оплата.
function productGetAvailable($ownerId, $ownerType)
{
    $result = CRest::call(
        'crm.item.productrow.getAvailableForPayment',
        [
            'ownerId' =>  $ownerId,
            'ownerType' => $ownerType,
        ]
    );
    return $result;
}

// Метод создает новую товарную позицию с полями fields
function productAdd($fields)
{
    $result = CRest::call(
        'crm.item.productrow.add',
        [
            'fields' =>  $fields,
        ]
    );
    return $result;
}
//============================================================================

// Возвращает описание полей товара.
function crmProductFieldsGet()
{
    $result = CRest::call(
        'crm.product.fields',
        []
    );
    return $result;
}


// Метод добавляет товар торгового каталога. Если операция успешна, возвращается ресурс товара торгового каталога в теле ответа.
function crmProductAdd($fields)
{
    $result = CRest::call(
        'crm.product.add',
        [
            "fields" => $fields,
        ]
    );
    return $result;
}

// Возвращает список товаров по фильтру. Является реализацией списочного метода для товаров. Ожидается, что в фильтре будет определён параметр CATALOG_ID. В противном случае товары будут выбираться из каталога по умолчанию.
function crmProductGetList($filter = null, $order = null, $select = ['*', 'PROPERTY_*'])
{
    $result = CRest::call(
        'crm.product.list',
        [
            "filter" => $filter,
            "order" => $order,
            "select" => $select,
        ]
    );
    return $result;
}
