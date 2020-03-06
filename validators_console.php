#!/usr/bin/php
<?php

//$skip_w2 = 1;
include "conf.php";

include "z_wals.php";

//$debug = 1;


unset($t,$t2);

unset($my);

foreach($wals as $k=>$v)
{
    $k2 = $v[mn_pk];
    if(!$k2)continue;
    $my[$v[mn_pk]] = $k;
}

if($debug)print_r($my);

$time = time();

if($argv[1]=="nolimit")$no_limit = 1;

while($time>(time()-59) || $no_limit)
{

unset($out);
unset($out2);
unset($flag_mas);
unset($block_mas);
unset($glob[color_set]);

$a = $cache_dir."/last_block";

$a = file_get_contents($a);
$block = $a;

$flag = 0;
if($last_block == $block){$flag=1;sleep(1);}
if($flag)continue;


if($last_block<$block)
$last_block = $block;

    $val = $last_block;
    $t = $val/120;
    $t2 = $t;
    $t2 = explode(".",$t2);
    $t2 = $t2[0];
    $t3 = $t2*120;
    $t4 = $val-$t3;

$round_blk = $t4;
$round_wait = 120-$t4;

print "\033c";

$info_line = "\n=================== last_block = \033[01;32m$last_block\033[00m round[120*$t2]:$t3+\033[38;95m$t4\033[00m [".date("Y-m-d H:i:s")."] ==========================================\n\n";
print $info_line;



for($i=1;$i<25;$i++)
{
//    $b = $last_block-$i;
    $b = $block-$i;

$block_mas[$b] = $b;
    unset($file_mas);
$b2 = view_number($b,8);
if($debug)print "=== $i = $b ================\n";
$b2 = direr($b2);
$d = $cache_dir."/$b2";
$d2 = dirname($d);

    $file_mas[block] = "$d.b";;
    $file_mas[validators] = "$d.v";;
    $file_mas[candidates] = "$d.c";;

    foreach($file_mas as $k=>$file)
    {
    $flag = 0;
    if(file_exists($file))$flag = 1;
if($debug)print $file." $flag\n";
    if(!file_exists($file))
    $block_err[$b][$file] = $file;

    if(file_exists($file))
    {
        $a = file_get_contents($file);
    }
    else
    continue;

        $a = json_decode($a,1);
        switch($k)
        {
            case "block":
                $mas = $a[result][validators];
                foreach($mas as $v)
                {
                    $out[$v[pub_key]][$b][$k] = $v[signed];
                }
            break;
            case "validators":
                $mas = $a[result];
                foreach($mas as $v)
                {
//                  $out[$v[pub_key]][$b][$k] = $v[voting_power];
                    $out[$v[pub_key]][$b][$k] = 1;
                }

            break;
            case "candidates":
            //print_r($a);die;
                $mas = $a[result];
                foreach($mas as $v)
                {
                    $out[$v[pub_key]][$b][$k] = $v[status];
		    $t = $v[total_stake];
		    $t = pip2dec($t);
		    $t = ceil($t);
		    $len = strlen($t);
		    if($rating_len <$len)$rating_len = $len;
		    $rating[$k][pk][$v[pub_key]] = $t;
                }

            break;
        }
    }
}
arsort($rating[candidates][pk]);



//$
$validator_mas = array_keys($out);
krsort($block_mas);


//$flag_mas
reset($my);
foreach($rating[candidates][pk] as $wal=>$rate)
{
unset($flag_mas);
//    foreach($out[$wal] as $pk=>$v)
    {
$v = $out[$wal];
        reset($block_mas);
        $nn = 0;
        foreach($block_mas as $blk)
        {
        $nn++;
        if($nn==1)continue;

            $k = "candidates";
            //print_mas($v[$blk][$k]);
            if($v[$blk][$k]==2 && !$flag_mas[$k])
            $out2[$wal][$k]++;
            else
            $flag_mas[$k]++;

            $k = "validators";
            if($v[$blk][$k]==1 && !$flag_mas[$k])
            $out2[$wal][$k]++;
            else
            $flag_mas[$k]++;

            $k = "block";
            if($v[$blk][$k]==1)
            {
                if(!$flag_mas[$k])
                $out2[$wal][$k]++;
            }
            else
            {
//                $out2[$wal]["un".$k][] = $blk;
//                $flag_mas[$k]++;
                if(isset($v[$blk][$k]))
                {
                $out2[$wal]["un".$k][] = $blk;
                $flag_mas[$k]++;
                }

            }


        }
    }

}

$nn = 0;
foreach($out2 as $pk=>$v)
{
$nn++;
if($nn>55)continue;
unset($l);
$unblock = count($v[unblock]);

if(!$v[candidates])
{
$unblock = 0;
unset($v[unblock]);
$v[VALIDATOR] = "OFFline";
	$color = get_color("offline");
}

if(($v[candidates] && !$v[validators]))
{
$unblock = 0;
unset($v[unblock]);
$v["Wait for start"] = $round_wait;
	$color = get_color("candidate");
}
//print "unblock = $unblock\n";

//print_r($v);

    if($unblock && !$v[block] && $unblock>=0 && $unblock<4)	$color = get_color("error");
    if($unblock && !$v[block] && $unblock>=4 && $unblock<7)	$color = get_color("fatal");
    if($unblock && !$v[block] && $unblock>6)			$color = get_color("critical");
    if($unblock && $v[block])					$color = get_color("recovery");
    if(!$unblock && $v[block])					$color = get_color("validator");

    $l[] = $nn;

    $t = "";

    $is_my = $my[$pk];
    if(!$is_my)
    {
	$t2 = substr($pk,0,8);
	$t2 .= "...";
	$t2 .= substr($pk,strlen($pk)-7);
	$t = $t2;
        $c =  get_color("unknown");
    }
    else
    {
    $c =  get_color("my");
    $t = $my[$pk];
    }


    for($i=strlen($t);$i<18;$i++)
    $t .= " ";
    $t = $c." ".$t." \033[00m";
    $l[] = $t; 

    $l[] = $color." $pk \033[00m";

    $t2 = $rating[candidates][pk][$pk];
$b_all += $t2;
    $t2 = view_number($t2,$rating_len," ");

    $len = strlen($t2);
    $tn = 0;
    $t2 = strrev($t2);
    $t3 = "";
    for($i=0;$i<$len;$i+=3)
    {
	$t3 .= substr($t2,$i,3)." ";
    }
    $t3 = strrev($t3);
    $l[] = $t3;


    reset($v);
    unset($t);
    foreach($v as $k2=>$v2)
    {
	$k3 = "\033[9;36m$k2:\033[00m";
	switch($k2)
	{
	    case "unblock":
		$t3 = $out2[$pk][unblock];
		//$tt = count($v2);
		$t2 = "(\033[38;41m".implode("\033[00m,\033[38;41m",$t3)."\033[00m)";

	        if($unblock>=$wals[$my[$pk]][fail_count] && !$v[block])
		{
		unset($m);
		$m[cmd] = $wals[$my[$pk]][fail_cmd];
		fail_cmd($m);
		}

	        if($unblock>=$wals[$my[$pk]][fail2_count] && !$v[block])
		{
		unset($m);
		$m[cmd] = $wals[$my[$pk]][fail2_cmd];
		fail_cmd($m);
		}

	        $val = $k3."$unblock $t2";
	    break;
	    default:
		$val = $v2;
		if($val == 0)$c = "\033[2;91m";
		if($val > 0)$c = "\033[1;36m";
		if($val > 5)$c = "\033[0;110m";
		if($val > 12)$c = "\033[1;92m";
		$val = $k3.$c.$val."\033[00m ";
	}
    $t .= $val;
    }
    if(!$t)    $t = "\033[01;33mValidator off\033[00m";
    $l[] = $t;

    print implode("\t",$l)."\n";
}

if($glob[color_set])
{
$t = implode(" ",$glob[color_set]);
print "\nUsed color name: ". $t."\n";
}
print $info_line;
sleep(1);
}

?>
