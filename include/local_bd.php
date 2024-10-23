<?php


function create_BD($path_for_data, $file_data)
{
    $result = [];
    // проверяем наличие каталога и файла с BD
    if (!file_exists($path_for_data)) {
        // если нет то создаём каталог и пустой файл
        mkdir($path_for_data, 0755, true);
        $save = file_put_contents($path_for_data . $file_data, '');
        $result["save"] =  $save;
        if ($save === false) {
            $result["error"] = 'Ошибка при создании пустого файла в новом каталоге';
        }
    } elseif (!file_exists($path_for_data . $file_data)) {
        // если нет то создаём пустой файл
        $save = file_put_contents($path_for_data . $file_data,  '');
        $result["save"] =  $save;
        if ($save === false) {
            $result["error"] = 'Ошибка при создании пустого файла в существующем каталоге';
        }
    }
    return $result;
}



// функция до записи данных в файл в новую строку
function write_append_DB($path_for_data, $file_data, $attempt = 10, $data)
{
    // создаём файл если его нет
    create_BD($path_for_data, $file_data);

    // номер попытки записать данные в файл
    $number_of_attempt = (int)1;

    do {
        // случайная пауза между попытками
        $time_sleep = random_int(100, 999);
        usleep($time_sleep);
        // открываем файл на запись
        $file = fopen($path_for_data . $file_data, 'a');
        // блокируем файл на время записи
        if (flock($file, LOCK_EX)) {
            // до записываем данные в файл
            fwrite($file,  $data . PHP_EOL);
            // снимаем блокировку с файла
            flock($file, LOCK_UN);
            fclose($file);
        } else {
            // если не получилось заблокировать то будем делать ещё попытку
            fclose($file);
            $file = false;
        }
        // закрываем файл
        // задаём десять попыток доступа к файлу
        $number_of_attempt = $number_of_attempt + 1;
    } while (($number_of_attempt <  $attempt) and ($file === false));

    // если файл недоступен то возвращаем null
    if ($file === false) {
        $file = null;
        return $file;
    }
    return $file;
}





// функция чтения данных из файла без блокировки
function read_DB($path_for_data, $file_data, $attempt = 10)
{
    // создаём файл если его нет
    create_BD($path_for_data, $file_data);

    // номер попытки получить данные из файла
    $number_of_attempt = (int)1;
    do {
        // случайная пауза между попытками
        $time_sleep = random_int(100, 999);
        usleep($time_sleep);
        // записываем данные в переменную данные из файла
        $file = file_get_contents($path_for_data . $file_data);
        // задаём десять попыток доступа к файлу
        $number_of_attempt = $number_of_attempt + 1;
    } while (($number_of_attempt <  $attempt) and ($file === false));

    // если файл недоступен то возвращаем null
    if ($file === false) {
        $file = null;
        return $file;
    }
    return $file;
}



// функция получения данных из файла и удаление данных из него файла
function read_delete_DB($path_for_data, $file_data, $attempt = 10)
{
    // создаём файл если его нет
    create_BD($path_for_data, $file_data);

    // номер попытки записать данные в файл
    $number_of_attempt = (int)1;

    do {
        // случайная пауза между попытками
        $time_sleep = random_int(100, 999);
        usleep($time_sleep);
        // открываем файл на чтение
        $file = fopen($path_for_data . $file_data, 'r');
        // блокируем файл на время
        if (flock($file, LOCK_EX)) {
            // читаем данные из файла
            $file_content = fread($file, filesize($path_for_data . $file_data));
            // закрываем файл, но не снимаем блокировку
            fclose($file);
            // открываем файл на запись
            $file = fopen($path_for_data . $file_data, 'w');
            // записываем пустые данные в файл
            fwrite($file,  '' . PHP_EOL);
            // снимаем блокировку с файла
            flock($file, LOCK_UN);
            // закрываем файл
            fclose($file);
        } else {
            // если не получилось заблокировать то будем делать ещё попытку
            fclose($file);
            $file = false;
        }
        // задаём десять попыток доступа к файлу
        $number_of_attempt = $number_of_attempt + 1;
    } while (($number_of_attempt <  $attempt) and ($file === false) and ($file_content === false));

    // если файл недоступен то возвращаем null
    if ($file_content === false) {
        $file_content = null;
        return $file_content;
    }
    return $file_content;
}
