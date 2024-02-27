<?php

function getDates($startTime, $endTime) {

    $day = 86400;
    $format = 'Y-m-d';
    $startTime = strtotime($startTime);
    $endTime = strtotime($endTime);
    //$numDays = round(($endTime - $startTime) / $day) + 1;
    $numDays = round(($endTime - $startTime) / $day); // remove increment

    $days = array();


    $days[] = date($format, $startTime);

    for ($i = 1; $i < $numDays; $i++) { //change $i to 1
        $days[] = date($format, ($startTime + ($i * $day)));
    }

    $days[] = date($format, $endTime);


    return $days;
}



/* функція здійснює пошук параметрів в массиві 'event_params' */
function find_params_in_event_params($arr, $params){

    $params_value_type = 'string_value';
    $res = [];

    foreach($arr as $item){
        if( in_array($item['key'], $params) ){
            $res[ $item['key'] ] = $item['value'][$params_value_type];
        }

        if( count($res) == count($params) ) break;
    }

    return $res;
}




function timestamp_to_microsecond($timestamp){
    return strtotime($timestamp) * 1000000; // '2010-05-17 19:13:37' => 1274123617
}




function microsecond_to_timestamp($microsecond){

    return date("Y-m-d H:i:s", ($microsecond / 1000000));
}



function formated_phone($full_phone){
    return str_replace('+38', '', $full_phone);
}



function get_url_components($url) {

    $url_components = parse_url($url);


    if( Arr::exists($url_components, 'query') ){

        parse_str($url_components['query'], $params);
        $url_components['query_params'] = $params;
    }

    return $url_components;
}
