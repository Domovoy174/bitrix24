<?php

// Запуск бизнес процесса
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
