<?php
$text = '<item><value="qweg"><value="lsjnb123k"><value="asdas"></item><item><value="vfdv"><value="hfnd"><value="nghn"></item>';

$pattern = "#<item>(.*)<\/item>#U";

$matches = array();
preg_match_all($pattern, $text, $matches);

var_dump($matches);
// phpinfo();