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
	//sanitize
	if(!isset($_GET['id'])){
		die('You did not select any item, please go <a href="index.php">back</a> and try again!');
	}
	if(is_numeric($_GET['id'])){
		$id = $_GET['id'];
	} else {
		die('This is not a valid item, please go <a href="index.php">back</a> and try again!');
	}
	
	//check db if id exists
	include('con.php');
	$q = mysqli_query($con, "SELECT COUNT(*) FROM units WHERE id='".$id."'");
	while($rez=mysqli_fetch_array($q)){
		$result = $rez[0];
	}
	
	if($result != 1){
		die("Error: ID does not exist or there is a duplicate in your database. Please contact site administrator at site.administrator@test.com!");
	} else {
		$sql = mysqli_query($con, "DELETE FROM units WHERE id='".$id."'");
		if(!$sql){
			echo "Could not delete entry. Please contact site administrator at site.administrator@test.com!";
		} else {
			header('Location: index.php');
		}
	}
	
?>
</body>
</html>