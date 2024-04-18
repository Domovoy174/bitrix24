<?php

/*
    ver 1.0.0 
    date: 04.04.2024
    who changed it: Admin_2
*/


// Переименовывание папки на диске
function folderRename($folderID, $newFolderName)
{
    $result = CRest::call(
        'disk.folder.rename',
        [
            'id' => $folderID,
            'newName' => $newFolderName,
        ]
    );
    return $result;
}


// Информация о папки на диске
function folderGetInfo($folderID)
{
    $result = CRest::call(
        'disk.folder.get',
        [
            'id' => $folderID,
        ]
    );
    return $result;
}
