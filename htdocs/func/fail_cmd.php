<?php
function fail_cmd($mas)
{
extract($mas,EXTR_OVERWRITE);
//                $cmd = $wals[$my[$pk]][fail_cmd];
                $t2 .= "\n".$cmd;
                if(1)
                {
                exec($cmd,$reg);
                $t = implode("\n",$reg);
		$log[] = "==============================";
		$log[] = date("Y-m-d H:i:s");
		$log[] = $cmd;
		$log[] = $t;
		$txt = implode("\n",$log);
                //$t4 = .$cmd."\t".$t."\n";
                $f = __FILE__.".exec_log";
                $f = fopen($f,"a+");
                fwrite($f,$txt);
                fclose($f);
		return $t;
                }

}
?>