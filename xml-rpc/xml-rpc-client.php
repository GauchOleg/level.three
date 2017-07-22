<?php
function call_socket($remote_server,$remote_server_port, $remote_path, $request){
    $sock = fsockopen($remote_server,$remote_server_port,$error_no,$error_str,30);
    if (!$sock) die("$error_str($error_no)\r\n");

    $out = "POST $remote_path HTTP/1.1\r\n";
    $out .= "User-Agent: PHPRPC/1.0\r\n";
    $out .= "Host: $remote_server\r\n";
    $out .= "Content-Type: text/xml\r\n";
    $out .= "Content-length: " . strlen($request) . "\r\n";
    $out .= "Accept: */*\r\n\r\n";
    $out .= "$request\r\n\r\n";
    fputs($sock,$out);

    $headers = '';
    while ($str = trim(fgets($sock, 4096))){
        $headers .= $str . "\r\n";
    }
    $data = "";
    while (!feof($sock)){
        $data .= fgets($sock,4096);
    }
    fclose($sock);
    return $data;

}
header('Content-Type: text/xml; charset=utf-8');
// Сюда приходят данные с сервера
$output = array();
// Основная функция
function make_request($request_xml, &$output){
    // начало запроса
    $retval = call_socket('level.three', 80, '/xml-rpc/xml-rpc-server.php', $request_xml);
//    $opts = array(
//        'http' => array(
//            'method' => 'POST',
//            'header' => "User-Agent: PHPRPC/1.0\r\n" . "Content-Type: text/xml\r\n" .
//            "Content-length: " . strlen($request_xml) . "\r\n",
//            'content' => "$request_xml"
//        )
//    );
//    $context = stream_context_create($opts);
//    $retval = file_get_contents('http://level.three/xml-rpc/xml-rpc-server.php', false, $context);
//    // конец запроса
//    $data = xmlrpc_decode($retval);
//    if (is_array($data) && xmlrpc_is_fault($data)){
//        $output = $data;
//    }else{
//        $output = unserialize(base64_decode($data));
//    }
}
// индификатор статьи
$id = 1;
$request_xml = xmlrpc_encode_request('getNewsById', array($id));
make_request($request_xml, $output);
// вывод результата
var_dump($output);

?>