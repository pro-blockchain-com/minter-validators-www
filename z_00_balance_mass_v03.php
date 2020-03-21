#!/usr/bin/php
<?php

include "conf.php";

$kuda = $nodeUrl."/status";
$a = file_get_contents($kuda);
$a = json_decode($a,1);
//print_r($a);die;

$time_adder = 3600*3;
$blk = $a[result][latest_block_height];
$t = $a[result][latest_block_time];
$t2 = explode(".",$t);
$t = $t2[0];
$t = str_replace("T"," ",$t);
//$t
$t = strtotime($t);
$t += $time_adder;
$delta = time()-$t;
$t = date("Y-m-d H:i:s",$t);


require_once __DIR__ . '/vendor/autoload.php';
print "-------------- ". date("Y-m-d H:i:s")." -- delta: $delta sec ------ $blk [$t] ------------\n";
use Minter\MinterAPI;
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterMultiSendTx;
foreach($wals as $wname=>$wal2)
{
$wal = $wal2[wal];
//print "========================= $wname [$wal] ==========\n";
print "\033[01;32m $wname \t[$wal] \033[00m \t";
$balance = $api->getBalance($wal);
$balance = $balance->result->balance;
//print_r($balance);die;
$bal = (array)$balance;
$b = $bal[$def_coin]/$devider;
$b = explode(".",$b);
$b = $b[0];

unset($summ);
//print "banace_$def_coin = $b\n";
unset($bal2);
foreach($bal as $coin=>$val2)
{

	$val = $val2/$devider;
//	$val = round($val,2);
	$val = floor($val);
	$bal2[$coin] = $val;
//estimateCoinSell(string $coinToSell, string $valueToSell, string $coinToBuy, ?int $height = null):
if($coin != "$def_coin")
{
//error_reporting(2039);
$t = $api->estimateCoinSell($coin, $val2, "$def_coin");
//print "\$api->estimateCoinSell($coin, $val2, \"$def_coin\");\n";
//$t = $api->estimateCoinBuy("$def_coin", $val2, $coin);
//print_r( $t2);
//print "\$api->estimateCoinBuy(\"$def_coin\", $val2, $coin);\n";

$r = $t->result;
$r = (array)$r;
//print_r( $t);
$smnt = $t->result->will_get;
$summ += $smnt;

	$bal2[$coin] .= " [".floor($smnt/$devider)."]" ;
//die;
//print "=====================\n";
}
else
$summ+=$val2;
//estimateCoinSell

}
//print_r($bal2);
$summ = $summ/$devider;
print " summ = \033[01;35m $summ \033[00m\n";
if($summ>=1)
foreach($bal2 as $kk=>$vv)
{
    print "\t\t$kk: $vv\n";
}
//print "\n";
//print_r($bal2);

}



?>