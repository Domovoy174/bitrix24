<?php

/*
    ver 1.0.0 
    date: 04.04.2024
    who changed it: Admin_2
*/

// функция для отправки сообщения в чат
function postToChat($message, $chat_id)
{
	$result = CRest::call(
		'im.message.add',
		[
			'DIALOG_ID' => $chat_id,
			'MESSAGE' => print_r($message, true),
		]
	);
	return $result;
}
