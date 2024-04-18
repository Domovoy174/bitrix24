<?php

/*
    ver 1.0.0 
    date: 04.04.2024
    who changed it: Admin_2
*/


function checkStage($item, $stageId)
{
    $res = false;
    if (isset($item) and isset($item["stageId"]) and $item["stageId"] === $stageId) {
        $res = true;
    };
    return $res;
}


function checkStageSuccess($item)
{
    $res = false;
    if (isset($item) and isset($item["stageId"])) {
        $pos = strpos($item["stageId"], ":");
        $str = substr($item["stageId"], $pos + 1);
        if (($str === "SUCCESS") or ($str ===  "P")) {
            $res = true;
        }
    };
    return $res;
}


function checkStageFail($item)
{
    $res = false;
    if (isset($item) and isset($item["stageId"])) {
        $pos = strpos($item["stageId"], ":");
        $str = substr($item["stageId"], $pos + 1);
        if (($str === "FAIL") or  ($str ===  "D")) {
            $res = true;
        }
    };
    return $res;
}
