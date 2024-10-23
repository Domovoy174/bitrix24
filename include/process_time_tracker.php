<?php

/*
    ver 1.0.0
    date: 27.06.2024
    who changed it: Admin_2
*/


function decompositionPerDay($start_work_time, $end_work_time, $arrival_time, $departure_time, $days_off_week = null, $holiday_dates = null)
{

    $counter_day = (int)0;

    $time_start_time_hour = date("H", strtotime($arrival_time));
    $time_start_time_minutes = date("i", strtotime($arrival_time));

    $time_end_time_hour = date("H", strtotime($departure_time));
    $time_end_time_minutes = date("i", strtotime($departure_time));

    list($start_work_time_hour, $start_work_time_minutes) = decompositionTime($start_work_time);
    list($end_work_time_hour, $end_work_time_minutes) = decompositionTime($end_work_time);

    // textPostToBrowser('arrival_time', $arrival_time);
    // textPostToBrowser('departure_time', $departure_time);

    // разница в днях между датами
    if (!empty($days_off_week) and !empty($holiday_dates)) {
        $counter_day =  countDayOffWeekends($departure_time, $arrival_time, $days_off_week, $holiday_dates);
    } else {
        $counter_day =  countDay($departure_time, $arrival_time);
    }

    // textPostToBrowser('full counter_day', (int)$counter_day + 1);

    $result_work_time = (int)0;
    $result_non_working_time = (int)0;

    if ($counter_day > 0) {
        // несколько дней. Запускаем цикл
        for ($i = 0; $i <= $counter_day; $i++) {
            echo "<br>";
            echo "_____________________________________________________";
            echo "<br>";
            echo "counter_day = " . $i;
            echo "<br>";
            // первый день
            if ($i === 0) {
                // первый день периода
                // передаём дополнительный параметр для точного расчёта времени и перевода на другие сутки
                $resultTime = decompositionOfTime(
                    $start_work_time_hour,
                    $start_work_time_minutes,
                    $end_work_time_hour,
                    $end_work_time_minutes,
                    $time_start_time_hour,
                    $time_start_time_minutes,
                    "23",
                    "59",
                    true
                );
            } elseif ($i === $counter_day) {
                // последний день периода
                $resultTime = decompositionOfTime(
                    $start_work_time_hour,
                    $start_work_time_minutes,
                    $end_work_time_hour,
                    $end_work_time_minutes,
                    "00",
                    "00",
                    $time_end_time_hour,
                    $time_end_time_minutes
                );
            } else {
                // дни в середине периода
                // передаём дополнительный параметр для точного расчёта времени и перевода на другие сутки
                $resultTime = decompositionOfTime(
                    $start_work_time_hour,
                    $start_work_time_minutes,
                    $end_work_time_hour,
                    $end_work_time_minutes,
                    "00",
                    "00",
                    "23",
                    "59",
                    true
                );
            }
            // подсчёт времени
            $result_work_time = $result_work_time + (int)$resultTime["work_time"];
            $result_non_working_time = $result_non_working_time + (int)$resultTime["non_working_time"];
        }
    } elseif ($counter_day === 0) {
        echo "<br>";
        echo " counter_day === 0 ";
        echo "<br>";
        // в рамках одного дня
        $resultTime = decompositionOfTime(
            $start_work_time_hour,
            $start_work_time_minutes,
            $end_work_time_hour,
            $end_work_time_minutes,
            $time_start_time_hour,
            $time_start_time_minutes,
            $time_end_time_hour,
            $time_end_time_minutes
        );
        // подсчёт времени
        $result_work_time = $result_work_time + (int)$resultTime["work_time"];
        $result_non_working_time = $result_non_working_time + (int)$resultTime["non_working_time"];
    } elseif ($counter_day < 0) {
        // ошибка Неправильно стоят даты начала и окончания ERROR
        // echo "<br>";
        // echo "ERROR counter_day < 0 ";
        // echo "<br>";
        $result_work_time = (int)0;
        $result_non_working_time = (int)0;
    }
    $result = [
        "work_time" => $result_work_time,
        "non_working_time" => $result_non_working_time,
    ];
    return $result;
}

function decompositionOfTime(
    $start_work_time_hour,
    $start_work_time_minutes,
    $end_work_time_hour,
    $end_work_time_minutes,
    $time_start_time_hour,
    $time_start_time_minutes,
    $time_end_time_hour,
    $time_end_time_minutes,
    $params = null
) {
    // ВНИМАНИЕ формат времени как строка со значением:  08:00  или  02:17
    // отработано в рабочее время
    $result_work_time = (int)0;
    // отработано до наступления рабочего времени
    $result_non_working_time_before = (int)0;
    // отработано после окончания рабочего времени
    $result_non_working_time_after = (int)0;
    // отработано в нерабочего времени в общем
    $result_non_working_time = (int)0;
    //
    $start_work = strtotime('01-01-2020 '  . $start_work_time_hour . ':' . $start_work_time_minutes . ':00');
    $end_work = strtotime('01-01-2020 '  . $end_work_time_hour . ':' . $end_work_time_minutes . ':00');
    $time_start = strtotime('01-01-2020 '  . $time_start_time_hour . ':' . $time_start_time_minutes . ':00');
    // если дополнительный параметр передан значит подсчёт необходимо вести до наступления следующего дня
    if (isset($params) and $params === true) {
        $time_end = strtotime('02-01-2020 00:00:00');
    } else {
        $time_end = strtotime('01-01-2020 '  . $time_end_time_hour . ':' . $time_end_time_minutes . ':00');
    }

    // (СЧИТАЕМ ВНЕУРОЧНОЕ ВРЕМЯ)  если работа произведена до наступления урочного времени
    if ($time_start < $start_work and $time_end <= $start_work) {
        $result_non_working_time_before = $result_non_working_time_before + ($time_end - $time_start);
        echo "<br>";
        echo 'criterion (1) result_non_working_time_before = ' .  $result_non_working_time_before;
        echo "<br>";
    }
    // (СЧИТАЕМ ВНЕУРОЧНОЕ ВРЕМЯ)  если работа произведена частично до наступления урочного времени
    if ($time_start < $start_work and $time_end > $start_work) {
        $result_non_working_time_before = $result_non_working_time_before + ($start_work - $time_start);
        echo "<br>";
        echo 'criterion (2) result_non_working_time_before = ' .  $result_non_working_time_before;
        echo "<br>";
    }
    // (СЧИТАЕМ ВНЕУРОЧНОЕ ВРЕМЯ)  если работа произведена частично после урочного времени
    if ($time_start < $start_work and $time_end > $end_work) {
        $result_non_working_time_after = $result_non_working_time_after + ($time_end - $end_work);
        echo "<br>";
        echo 'criterion (3) result_non_working_time_after = ' .  $result_non_working_time_after;
        echo "<br>";
    }
    // (СЧИТАЕМ ВНЕУРОЧНОЕ ВРЕМЯ)  если работа произведена частично после урочного времени
    if ($time_start >= $start_work and $time_start < $end_work  and $time_end > $end_work) {
        $result_non_working_time_after = $result_non_working_time_after + ($time_end - $end_work);
        echo "<br>";
        echo 'criterion (4) result_non_working_time_after = ' .  $result_non_working_time_after;
        echo "<br>";
    }
    //  (СЧИТАЕМ ВНЕУРОЧНОЕ ВРЕМЯ)  если работа произведена после урочного времени
    if ($time_start >= $end_work) {
        $result_non_working_time_after = $result_non_working_time_after + ($time_end - $time_start);
        echo "<br>";
        echo 'criterion (5) result_non_working_time_after = ' .  $result_non_working_time_after;
        echo "<br>";
    }
    //  (СЧИТАЕМ УРОЧНОЕ ВРЕМЯ)  если работа произведена полностью в урочное время
    if ($time_start >= $start_work and $time_start < $end_work  and $time_end <= $end_work) {
        $result_work_time = $result_work_time + ($time_end - $time_start);
        echo "<br>";
        echo 'criterion (6) result_work_time = ' .  $result_work_time;
        echo "<br>";
    }
    // (СЧИТАЕМ УРОЧНОЕ ВРЕМЯ) если работа произведена частично в урочное и частично до
    if ($time_start < $start_work and $time_end <= $end_work and $time_end > $start_work) {
        $result_work_time = $result_work_time + ($time_end - $start_work);
        echo "<br>";
        echo 'criterion (7) result_work_time = ' .  $result_work_time;
        echo "<br>";
    }
    // (СЧИТАЕМ УРОЧНОЕ ВРЕМЯ) если работа произведена частично в урочное и частично до и частично  после
    if ($time_start < $start_work and $time_end > $end_work) {
        $result_work_time = $result_work_time + ($end_work - $start_work);
        echo "<br>";
        echo 'criterion (8) result_work_time = ' .  $result_work_time;
        echo "<br>";
    }
    // (СЧИТАЕМ УРОЧНОЕ ВРЕМЯ) если работа произведена частично в урочное и частично  после
    if ($time_start >= $start_work and $time_start < $end_work and $time_end > $end_work) {
        $result_work_time = $result_work_time + ($end_work - $time_start);
        echo "<br>";
        echo 'criterion (9) result_work_time = ' .  $result_work_time;
        echo "<br>";
    }

    echo "<br>";
    echo 'result_non_working_time_before  сек = ' .  intval($result_non_working_time_before);
    echo "<br>";
    echo 'result_non_working_time_before  минуты = ' .  round(intval($result_non_working_time_before) / 60);
    echo "<br>";
    echo 'result_work_time  сек = ' .  intval($result_work_time);
    echo "<br>";
    echo 'result_work_time  минуты = ' .  round(intval($result_work_time) / 60);
    echo "<br>";
    echo 'result_non_working_time_after  сек = ' .  intval($result_non_working_time_after);
    echo "<br>";
    echo 'result_non_working_time_after  минуты = ' .  round(intval($result_non_working_time_after) / 60);
    echo "<br>";

    $result_non_working_time = $result_non_working_time_before + $result_non_working_time_after;
    $result = [
        "work_time" => $result_work_time,
        "non_working_time" => $result_non_working_time
    ];
    return $result;
}


function decompositionTime($time)
{
    // ВНИМАНИЕ формат времени как строка со значением:  08:00  или  02:17
    $pieces = explode(":", $time);
    $result = [
        $pieces[0],
        $pieces[1]
    ];
    return $result;
}

function convertTime($timeSec)
{
    $hours = floor(intval($timeSec) / 3600);
    $minutes = floor((intval($timeSec) % 3600) / 60);
    $result = $hours . ":" . $minutes;
    return $result;
}

function countDay($start_date, $end_date)
{
    $start = date('d.m.Y', strtotime($start_date));
    $end = date('d.m.Y', strtotime($end_date));
    // Creates DateTime objects
    $datetime1 = date_create($start);
    $datetime2 = date_create($end);
    // Calculates the difference between DateTime objects
    $interval = date_diff($datetime1, $datetime2);
    return $interval->days;
}

function countDayOffWeekends($end_date, $start_date, $days_off_week, $holiday_dates)
{
    /*
    формат days_off_week = [6, 0]  -  массив чисел
    формат holiday_dates = ['10.09.2024', '09.09.2024'] - массив дат в таком формате 'd.m.Y'
    */
    // количество дней
    $count = (int)-1;
    // массив с датами периода между старом и концом
    $dates = array();
    $start = date('d.m.Y', strtotime($start_date));
    $end = date('d.m.Y', strtotime($end_date));
    // берём период между датами
    $period = new DatePeriod(
        new DateTime($start . 'T00:00:00+03:00'),
        new DateInterval('P1D'),
        new DateTime($end  . 'T23:59:59+03:00')
    );
    // перебираем даты и заносим в массив
    foreach ($period as $key => $value) {
        $dates[] = $value->format('d.m.Y');
    }

    echo "holiday_dates";
    echo "<br>";
    print_r($holiday_dates);
    echo "<br>";
    echo "days_off_week";
    echo "<br>";
    print_r($days_off_week);
    echo "<br>";

    // saveLogAppendTxt("start_date");
    // saveLogAppendTxt($start . 'T00:00:00+03:00');

    // saveLogAppendTxt("end_date");
    // saveLogAppendTxt($end  . 'T23:59:59+03:00');

    // saveLogAppendTxt("dates");
    // saveLogAppendTxt($dates);

    foreach ($dates as $key => $value) {
        echo "<br>";
        echo  $value;
        echo "<br>";
        $value_str = strval($value);
        echo "<br>";
        $day_week = getdate(strtotime($value));
        if (in_array($day_week['wday'], $days_off_week)) {
            //
            echo "<br>";
            echo ' ИСКЛЮЧАЕМ ВЫХОДНОЙ ' . $value_str;
            echo "<br>";
            // saveLogAppendTxt(' ИСКЛЮЧАЕМ ВЫХОДНОЙ ' . $value_str);
        } elseif (in_array($value_str, $holiday_dates)) {
            echo "<br>";
            echo ' ИСКЛЮЧАЕМ Праздничный ' . $value_str;
            echo "<br>";
            // saveLogAppendTxt(' ИСКЛЮЧАЕМ Праздничный ' . $value_str);

            //
        } else {
            // если день не выходной и не праздничный то считаем его
            $count++;
            // saveLogAppendTxt(' Добавляем день ' . $value_str);
        }
        echo 'day = ' . $day_week['wday'];
        echo "<br>";
    }
    echo "<br>";
    echo 'count = ' . $count;
    echo "<br>";

    // saveLogAppendTxt("count");
    // saveLogAppendTxt($count);

    return $count;
}


function diffTimeSec($start_date, $end_date)
{
    $result = strtotime($end_date) - strtotime($start_date);
    return $result;
}
