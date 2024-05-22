<?php

    /*
    Последние правки от Админ Netit24
    Дата правок 22.05.2024
    */

// Запуск бизнесс процесса
function biznessProcessStart($templateID, $documentID, $parametrs)
{
    $result = CRest::call(
        'bizproc.workflow.start',
        [
            'TEMPLATE_ID' =>  $templateID,
            'DOCUMENT_ID' => $documentID,
            'PARAMETERS' => $parametrs,
        ]
    );
    return $result;
}

//Получение данных по запущенным БП
function biznessProcessGetList($documentID)
{
    $result = CRest::call(
        'bizproc.workflow.instances',
        [
            'select' => ["ID"],
            'filter' => ["DOCUMENT_ID" => $documentID]
        ]
    );
    return $result;
}

//отключение всех запущенных БП
function biznessProcessTerminate($id_bp)
{
    $result = CRest::call(
        'bizproc.workflow.terminate',
        [
            "ID" => $id_bp,
        ]
    );
    return $result;
}