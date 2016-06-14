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
	//mysql connection
	include('con.php');

	if(!isset($_GET['id'])){
		
	}
	
	
	if(isset($_GET['id'])){
		//check id validity
		$id = $_GET['id'];
		$chk = mysqli_query($con, "SELECT COUNT(*) FROM units WHERE id='".$id."'");
		while($res = mysqli_fetch_array($chk)){
			$rez = $res['0'];
		}
		
		if($rez != 1){
			header('Location: index.php');
			//echo $rez;
		}
		
		//get data from DB for specified id
		$sql = mysqli_query($con, "SELECT * FROM units WHERE id='".$id."'");
		if(!$sql){
			echo "Error executing query: ".mysqli_error($sql);
		}
		
		while($q = mysqli_fetch_array($sql, MYSQLI_ASSOC)){
			$part_no = $q['part_no'];
			$description = $q['description'];
			$anouncement = $q['anouncement_date'];
			$last_day_sale = $q['last_day_sale'];
			$replacement = $q['replacement'];
		}
		
		if(isset($_POST['submit'])){
			// colect data, sanitize and update item
			if(isset($_POST['p_no']) && isset($_POST['p_anounce'])){
				if(!empty($_POST['p_no']) && !empty($_POST['p_anounce'])){
					$p_no = mysqli_real_escape_string($con, filter_var($_POST['p_no'], FILTER_VALIDATE_STRING));
					$p_descr = mysqli_real_escape_string($con, filter_var($_POST['p_descr'], FILTER_VALIDATE_STRING));
					$p_anounce = mysqli_real_escape_string($con, filter_var($_POST['p_anounce'], FILTER_VALIDATE_STRING));
					$p_last_d_s = mysqli_real_escape_string($con, filter_var($_POST['p_last_d_s'], FILTER_VALIDATE_STRING));
					$p_repalcement = mysqli_real_escape_string($con, filter_var($_POST['p_replacement'], FILTER_VALIDATE_STRING));
					
					$update = mysqli_query($con, "UPDATE units 
												  SET 
												  	part_no='".$p_no."',
												  	description='".$p_desc."',
												  	anouncement_date='".$p_anounce."',
												  	last_day_sale='".$p_last_d_s."',
												  	replacement='".$p_replacement."'
												  WHERE
												  	id='".$id."'");
					if(!$update){
						echo "Error executing the update query: ".mysqli_error($update);
					} else {
						//header('Location: index.php');
						echo 'Record updated!';
					}
				} else {
					echo "Error: empty";
					echo $p_no .' '.$p_anounce;	
					
				}
			} else {
				echo "Error: not set";
			}
			
		} else {
			//show form
			?>
				<center>
					<form action="addit.php" method="POST">
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
						<label>Part #</label><br />
						<input type="text" name="p_no" id="p_no" class="data_i" value="<?php echo $part_no; ?>"><br />
						<label>Description</label><br />
						<input type="text" name="p_descr" id="p_descr" class="data_i" value="<?php echo $description; ?>"><br />
						<label>Anouncement</label><br />
						<input type="text" name="p_anounce" id="p_anounce" class="data_i" value="<?php echo $anouncement; ?>"><br />
						<label>Last Day of Sale</label><br />
						<input type="text" name="p_last_d_s" id="p_last_d_s" class="data_i" value="<?php echo $last_day_sale; ?>"><br />
						<label>Replacement</label><br />
						<input type="text" name="p_replacement" id="p_replacement" class="data_i" value="<?php echo $replacement; ?>"><br />
						
						<input type="submit" name="save" id="save" class="save no" value="Save">
						<a href="index.php"><input type="button" name="return" id="return" class="save, yes" value="Return"></a>
					</form>
				</center>
			<?php
		}
	} else {
		header('Location: index.php');
	}
?>