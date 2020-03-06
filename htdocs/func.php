<?php

$dir_func = dirname(__FILE__);
$dir_func .= "/func/";
    $h = opendir($dir_func);
    while($file = readdir($h))
    {
	if($file == ".." || $file == ".")continue;
	if($file[0]==".")continue;
	$this_file = $dir_func.$file;
	$temp = pathinfo($this_file);
	if($temp['extension'] != "php")continue;
//	print $file."\n";
	require_once($this_file);
    }
?>