<?php
	$obj = json_decode($_REQUEST[worldstate]);
	//echo(var_dump(($obj)));
	echo(json_encode($obj));
	
?>