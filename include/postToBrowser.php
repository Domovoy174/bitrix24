<?php

/*
    ver 1.0.2
    date: 08.04.2024
    who changed it: Admin_2
*/

function arrayPostToBrowser($title, $message)
{
    echo "<br>";
    echo "_____________   " . $title . "   ________________";
    echo "<br>";
    echo "<pre>";
    print_r($message);
    echo "</pre>";
    echo "<br>";
    echo "--------------------------------------------------";
    echo "<br>";
}


function textPostToBrowser($title, $message)
{
    echo "<br>";
    echo "_____________   " . $title . "   ________________";
    echo "<br>";
    echo  $message;
    echo "<br>";
    echo "--------------------------------------------------";
    echo "<br>";
}
