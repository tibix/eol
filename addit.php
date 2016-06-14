<?php ob_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="css/demo.css" />
	<link rel="stylesheet" type="text/css" href="css/component.css" />
</head>
<body>

<?php
include('con.php');

if(isset($_POST['p_no']) && isset($_POST['p_anounce'])){
	if(!empty($_POST['p_no']) && !empty($_POST['p_anounce'])){
		$p_no = mysqli_real_escape_string($con, filter_var($_POST['p_no'], FILTER_SANITIZE_STRING));
		$p_descr = mysqli_real_escape_string($con, filter_var($_POST['p_descr'], FILTER_SANITIZE_STRING));
		$p_anounce = mysqli_real_escape_string($con, filter_var($_POST['p_anounce'], FILTER_SANITIZE_STRING));
		$p_last_d_s = mysqli_real_escape_string($con, filter_var($_POST['p_last_d_s'], FILTER_SANITIZE_STRING));
		$p_replacement = mysqli_real_escape_string($con, filter_var($_POST['p_replacement'], FILTER_SANITIZE_STRING));
		
		$update = mysqli_query($con, "UPDATE units 
									  SET 
									  	part_no='".$p_no."',
									  	description='".$p_descr."',
									  	anouncement_date='".$p_anounce."',
									  	last_day_sale='".$p_last_d_s."',
									  	replacement='".$p_replacement."'
									  WHERE
									  	id='".$_POST['id']."'");
		if(!$update){
			echo "Error executing the update query: ".mysqli_error($update);
		} else {
			header('Location: index.php');
		}
	} else {
		echo "Error: empty";
	}
} else {
	echo "Error: not set";
}
?>