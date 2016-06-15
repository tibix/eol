<?php
	$host = 'localhost';
	$user = 'tibix';
	$pass = 'cr1nutz4';
	$db = 'eol';
	
	$con = mysqli_connect($host, $user, $pass, $db);
	if(!$con){
		echo "ERROR: could not connect to DB. <br>".mysqli_error($con);
	}
	
?>