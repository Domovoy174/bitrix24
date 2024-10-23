<?php

/**
 *
 * @version 2.3.1
 * @date 21.06.2024
 * @who-changed-it Admin_2
 *
 * @param $endDebugTime - end date of log collection. Format '26-05-2024'
 */

// преобразовать и сохранить в виде json
function saveToLogJson($input, $endDebugTime = null)
{
  $saveLog = true;
  $date_current = date('d-m-Y H:i:s');
  if ($endDebugTime) {
    $dateTimestampEnd = strtotime($endDebugTime);
    $dateTimestampCurrent = strtotime($date_current);
    if ($dateTimestampCurrent > $dateTimestampEnd) {
      $saveLog = false;
    }
  }
  if ($saveLog) {
    if (!file_exists('logs/')) {
      mkdir('logs/', 0755, true);
    }
    $data = json_encode($input, true);
    $save = file_put_contents('logs/' . $date_current . '_' . '.json', print_r($data, 1));
  } else {
    $save = 'The debugging time is over';
  }
  return $save;
}
// преобразовать и сохранить в виде xml
function saveToLogXML($input, $name = null, $endDebugTime = null)
{
  $saveLog = true;
  $date_current = date('d-m-Y H:i:s');
  if ($endDebugTime) {
    $dateTimestampEnd = strtotime($endDebugTime);
    $dateTimestampCurrent = strtotime($date_current);
    if ($dateTimestampCurrent > $dateTimestampEnd) {
      $saveLog = false;
    }
  }
  if ($saveLog) {
    if (!file_exists('logs/')) {
      mkdir('logs/', 0755, true);
    }
    $name = $name ?: $date_current . '_';
    $save = file_put_contents('logs/' . $name . '.xml', $input->saveXML());
  } else {
    $save = 'The debugging time is over';
  }
  return $save;
}

// преобразовать и сохранить в произвольном формате но с типом содержания xml
function saveTypeXmlToCustom($input, $name = null, $folder = 'logs', $expansion  = 'xml', $endDebugTime = null)
{
  $saveLog = true;
  $date_current = date('d-m-Y H:i:s');
  if ($endDebugTime) {
    $dateTimestampEnd = strtotime($endDebugTime);
    $dateTimestampCurrent = strtotime($date_current);
    if ($dateTimestampCurrent > $dateTimestampEnd) {
      $saveLog = false;
    }
  }
  if ($saveLog) {
    if (!file_exists($folder . '/')) {
      mkdir($folder . '/', 0755, true);
    }
    $name = $name ?: $date_current . '_';
    $save = file_put_contents($folder . '/' . $name . '.' . $expansion, $input->saveXML());
  } else {
    $save = 'The debugging time is over';
  }
  return $save;
}

// преобразовать и сохранить в виде json
function saveToLogTitleJson($input, $title, $endDebugTime = null)
{
  $saveLog = true;
  $date_current = date('d-m-Y H:i:s');
  if ($endDebugTime) {
    $dateTimestampEnd = strtotime($endDebugTime);
    $dateTimestampCurrent = strtotime($date_current);
    if ($dateTimestampCurrent > $dateTimestampEnd) {
      $saveLog = false;
    }
  }
  if ($saveLog) {
    if (!file_exists('logs/')) {
      mkdir('logs/', 0755, true);
    }
    $data = json_encode($input, true);
    $save =  file_put_contents('logs/' . $title  . '__' . $date_current . '_' . '.json', print_r($data, 1));
  } else {
    $save = 'The debugging time is over';
  }
  return $save;
}

// сохранить в виде текста
function saveToLogTxt($input, $endDebugTime = null)
{
  $saveLog = true;
  $date_current = date('d-m-Y H:i:s');
  if ($endDebugTime) {
    $dateTimestampEnd = strtotime($endDebugTime);
    $dateTimestampCurrent = strtotime($date_current);
    if ($dateTimestampCurrent > $dateTimestampEnd) {
      $saveLog = false;
    }
  }
  if ($saveLog) {
    if (!file_exists('logs/')) {
      mkdir('logs/', 0755, true);
    }
    $save =  file_put_contents('logs/' . $date_current . '_' . '.txt', print_r($input, 1));
  } else {
    $save = 'The debugging time is over';
  }
  return $save;
}

// сохранить в единый файл
function saveLogAppendTxt($input, $endDebugTime = null)
{
  $saveLog = true;
  $date_current = date('d-m-Y H:i:s');
  if ($endDebugTime) {
    $dateTimestampEnd = strtotime($endDebugTime);
    $dateTimestampCurrent = strtotime($date_current);
    if ($dateTimestampCurrent > $dateTimestampEnd) {
      $saveLog = false;
    }
  }
  if ($saveLog) {
    if (!file_exists('logs/')) {
      mkdir('logs/', 0755, true);
    }
    $title =  PHP_EOL
      . '__________   '
      . $date_current
      . '   __________'
      . PHP_EOL
      . PHP_EOL;
    $save =  file_put_contents('logs/' . 'general_log' . '.txt', print_r($title, 1) . PHP_EOL, FILE_APPEND);
    $save =  file_put_contents('logs/' . 'general_log' . '.txt', print_r($input, 1) . PHP_EOL, FILE_APPEND);

    // $file = substr(sprintf('%o', fileperms(__DIR__ . '/logs/general_log.txt')), -4);
    // if ($file !== '0755') {
    //     chmod(__DIR__ . '/logs/general_log.txt', "0755");
    // }
  } else {
    $save = 'The debugging time is over';
  }
  return $save;
}

// сохранить в файл с ошибками
function saveERRORToLogTxt($input)
{
  if (!file_exists('logs/')) {
    mkdir('logs/', 0755, true);
  }
  $date_current = date('d-m-Y H:i:s');
  $title =  PHP_EOL
    . '__________   '
    . $date_current
    . '   __________'
    . PHP_EOL
    . PHP_EOL;
  $save =  file_put_contents('logs/' . 'error' . '.txt', print_r($title, 1) . PHP_EOL, FILE_APPEND);
  $save =  file_put_contents('logs/' . 'error' . '.txt', print_r($input, 1) . PHP_EOL, FILE_APPEND);
  return $save;
}
