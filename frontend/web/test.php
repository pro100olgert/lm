<?php
$text = '<item><value="qweg"><value="lsjnb123k"><value="asdas"></item><item><value="vfdv"><value="hfnd"><value="nghn"></item>';

$pattern = "#<item>(.*)<\/item>#";

$matches = array();
preg_match_all($pattern, $text, $matches);

echo '<pre>';
var_dump($matches[1]);
echo '</pre>'