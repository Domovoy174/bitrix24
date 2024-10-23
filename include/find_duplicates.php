<?php


function find_duplicates_in_portal($email = null, $phone = null)
{
    $duplicate = 'false';
    $array_batch = [];
    $find_duplicate_email = [];
    $find_duplicate_phone = [];
    $find_duplicate_portal_request = [];

    if (isset($email)) {
        array_push($find_duplicate_email, $email);
        $array_batch['duplicate_email'] =  'crm.duplicate.findbycomm?' . http_build_query(array('type' => 'EMAIL', 'values' => $find_duplicate_email));
    };
    if (isset($phone)) {
        array_push($find_duplicate_phone, $phone);
        $array_batch['duplicate_phone'] =  'crm.duplicate.findbycomm?' . http_build_query(array('type' => 'PHONE', 'values' => $find_duplicate_phone));
    };

    $find_duplicate_portal_request = CRest::call('batch', [
        'halt' => '0',
        'cmd' => $array_batch,
    ])['result']['result'];

    if (isset($find_duplicate_portal_request['duplicate_email']) and isset($find_duplicate_portal_request['duplicate_phone'])) {
        if (count($find_duplicate_portal_request['duplicate_email']) > 0) $duplicate = 'true';
        if (count($find_duplicate_portal_request['duplicate_phone']) > 0) $duplicate = 'true';
    }

    $result = [
        'duplicate' => $duplicate,
        'find_duplicate_portal_request' => $find_duplicate_portal_request
    ];

    return $result;
};
