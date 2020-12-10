<?php
	for($i=0;$i<21;$i++){
		$j=32400+$i*1800;
		echo gmdate("H:i", $j) . "<br>";
	}
?>