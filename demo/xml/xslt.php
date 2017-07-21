<?php
	//Создание объекта XML
	$xml = new DOMDocument();
	//Загрузка XML документа
	$xml->load('catalog.xml');
	//Создание объекта XSL
	$xsl = new DOMDocument();
	//Загрузка XSL документа
	$xsl->load('catalog.xsl');
	//Создание XSLT парсера
	$proc = new XSLTProcessor($xsl);
	//Загрузка XSL объекта
	$proc->importStylesheet($xsl);
	//Парсинг
    echo $proc->transformToXML($xml);
?>