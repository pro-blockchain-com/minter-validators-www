<?php
if(!function_exists("get_color"))
{
//print "=========================================\n";
function get_color($name)
{

global $glob;

//print "name = $name\n";


$color_mas[critical]            = "\033[5;41m";
$color_mas[error]               = "\033[9;91m";
$color_mas[fatal]               = "\033[9;101m";
$color_mas[offline]             = "\033[1;47m";
//$color_mas[candidate]           = "\033[7;93m";
$color_mas[candidate]           = "\033[01;33m";
$color_mas[validator]           = "\033[9;42m";
//$color_mas[recovery]          = "\033[9;46m";
$color_mas[recovery]            = "\033[38;92m";
$color_mas[offline] 		= "\033[00;37m";
$color_mas[my]                  = "\033[6;104m";
$color_mas[unknown]		= "\033[01;30m";


/*
unset($t,$t2);
foreach($color_mas as $k=>$v)
{
    switch($k)
    {
        case "critical":
        break;
        default;
        $t2[] = "$v $k \033[00m";
    }
    $t[] = "$v $k \033[00m";
}
$color_mas[all]                 = implode(" ",$t);
$color_mas[all_no_flash]        = implode(" ",$t2);


//$t =
$color_mas[all_no_flash]        = implode(" ",$glob[color_set][$name]);
*/

$glob[color_set][$name] = $color_mas[$name]."[$name]\033[00m";

$glob[color_mas] = $color_mas;

return $color_mas[$name];

}
}
?>
