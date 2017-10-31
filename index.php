<?php

include 'CustomStreamWrapper.php';
stream_wrapper_register('custom', 'CustomStreamWrapper');

$xml = new DOMDocument();
$xml->load('test.xml');

$xsl = new DOMDocument();
$xsl->resolveExternals = true;
$xsl->substituteEntities = true;
$xsl->load('main.xsl');

$proc = new XSLTProcessor();
$proc->importStyleSheet($xsl); // compilation error

echo $proc->transformToXML($xml);
