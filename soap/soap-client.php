<?php
$client = new SoapClient("http://level.three/soap/news.wsdl");
try{
    //сколько всего новостей?
    $result = $client->getNewsCount();
    echo '<p>' . 'Всего новостей: ' . $result . '</p>';
    // сколько новостей в категории политика?
    $result = getNewsCountByCat(1);
    echo '<p>' . 'Всего новостей в разделе политика: ' . $result . '</p>';
    // покажем конктретную новость
    $result = $client->getNewsById(1);
    $news = unserialize(base64_decode($result));
    var_dump($news);
}catch(SoapFault $e){
    echo 'Операция: ' . $e->faultcode . ' вернула ошибку: ' . $e->getMessage();
}

?>