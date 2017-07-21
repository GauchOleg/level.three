<?php
header('Content-Type: text/xml; charset=utf-8');
// Сюда приходят данные с сервера
$output = array();
// Основная функция
function make_request($request_xml, &$output){
    // начало запроса
    $opts = array(
        'http' => array(
            'method' => 'POST',
            'header' => "User-Agent: PHPRPC/1.0\r\n" . "Content-Type: text/xml\r\n" .
            "Content-length: " . strlen($request_xml) . "\r\n",
            'content' => "$request_xml"
        )
    );
    $context = stream_context_create($opts);
    $retval = file_get_contents('http://level.three/xml-rpc/xml-rpc-server.php', false, $context);
    // конец запроса
    $data = xmlrpc_decode($retval);
    if (is_array($data) && xmlrpc_is_fault($data)){
        $output = $data;
    }else{
        $output = unserialize(base64_decode($data));
    }
}
// индификатор статьи
$id = 1;
$request_xml = xmlrpc_encode_request('getNewsById', array($id));
make_request($request_xml, $output);
// вывод результата
var_dump($output);

?>