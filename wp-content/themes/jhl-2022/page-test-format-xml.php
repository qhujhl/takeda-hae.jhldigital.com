<?php

$xml = '<test><a><b>Hello</b></a><c></c></test>';
$dom = new \DOMDocument('1.0');
$dom->preserveWhiteSpace = true;
$dom->formatOutput = true;
$dom->loadXML($xml);
$xml_pretty = $dom->saveXML();

print_r(htmlspecialchars($xml_pretty));