<?php
function direr($number=0)
{

        $len = strlen($number);
        $out = "";
        $devider = "/";
        for($i=0;$i<$len;$i+=2)
        {
                $out .= substr($number,$i,2).$devider;
        }
        $out = substr($out,0,strlen($out)-1);
        return $out;
}
?>