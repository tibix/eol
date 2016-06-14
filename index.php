<?php ob_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="css/demo.css" />
	<link rel="stylesheet" type="text/css" href="css/component.css" />
</head>
<body>
<div>
	<table>
		<thead>
			<tr>
				<th colspan="7" align="right">
					<form action="search.php" method="GET">
						<input type="text" id="search" name="search" class="search_bar" autocomplete="off" placeholder="Input your search string"/>
						<input type="submit" id="submit" name="submit" class="search" value="Search"  />
					</form>	
				</th>
			</tr>
			<tr>
				<form action="index.php" method="POST">
				<th><input type="text" name="part_no" id="part_no" class="data_i" placeholder="New Part NO" /></th>
				<th><input type="text" name="description" id="description" class="data_i" placeholder="New Description" /></th>
				<th><input type="text" name="anouncement" id="Anouncement" class="data_i" placeholder="New Announcement" /></th>
				<th><input type="text" name="last_day_sale" id="last_day_sale" class="data_i" placeholder="New Last Day Sale" /></th>
				<th><input type="text" name="replacement" id="replacement" class="data_i" placeholder="New Replacement" /></th>
				<th colspan='2'><input type="submit" id="save" name="save" class="save" value="Insert" /></th>
				</form>
			</tr>
			<tr>
				<th><a style="color: green;" href="<?php echo $_SERVER['PHP_SELF'] . "?sort=ASC.part_no";?>" >&#9650;</a> Part # <a style="color: darkorange;" href="<?php echo $_SERVER['PHP_SELF'] . "?sort=DESC.part_no";?>" >&#9660;</a></th>
				<th><a style="color: green;" href="<?php echo $_SERVER['PHP_SELF'] . "?sort=ASC.description";?>" >&#9650;</a> Description <a style="color: darkorange;" href="<?php echo $_SERVER['PHP_SELF'] . "?sort=DESC.description";?>">&#9660;</a></th>
				<th><a style="color: green;" href="<?php echo $_SERVER['PHP_SELF'] . "?sort=ASC.anouncement_date";?>" >&#9650; </a> Announcement Date <a style="color: darkorange;" href="<?php echo $_SERVER['PHP_SELF'] . "?sort=DESC.anouncement_date";?>">&#9660;</a></th>
				<th><a style="color: green;" href="<?php echo $_SERVER['PHP_SELF'] . "?sort=ASC.last_day_sale";?>" >&#9650;</a> Last Day of Sale <a style="color: darkorange;" href="<?php echo $_SERVER['PHP_SELF'] . "?sort=DESC.last_day_sale";?>">&#9660;</a></th>
				<th><a style="color: green;" href="<?php echo $_SERVER['PHP_SELF'] . "?sort=ASC.replacement";?>" >&#9650;</a> Replacement <a style="color: darkorange;" href="<?php echo $_SERVER['PHP_SELF'] . "?sort=DESC.replacement";?>">&#9660;</a></th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>	
<?php

include('con.php');

if(isset($_POST['save'])){
	if(isset($_POST['part_no']) && isset($_POST['description']) && isset($_POST['anouncement']) && isset($_POST['last_day_sale']) && isset($_POST['replacement'])){
		if(!empty($_POST['part_no']) && !empty($_POST['anouncement'])){
			//collect date and sanitize
			$part_no = mysqli_real_escape_string($con, filter_var($_POST['part_no'], FILTER_SANITIZE_STRING));
			if(!empty($_POST['description'])){
				$description = mysqli_real_escape_string($con, filter_var($_POST['description'], FILTER_SANITIZE_STRING));
			} else {
				$description = "none";
			}
			$anouncement = mysqli_real_escape_string($con, filter_var($_POST['anouncement'], FILTER_SANITIZE_STRING));
			if(!empty($_POST['last_day_sale'])){
				$last_day_sale = mysqli_real_escape_string($con, filter_var($_POST['last_day_sale'], FILTER_SANITIZE_STRING));
			} else {
				$last_day_sale = "not available";
			}
			if(!empty($_POST['replacement'])){
				$replacement = mysqli_real_escape_string($con, filter_var($_POST['replacement'], FILTER_SANITIZE_STRING));
			} else {
				$replacement = "not available";
			}
			
			$q = mysqli_query($con, "INSERT INTO units (part_no, description, anouncement_date, last_day_sale, replacement) 
									VALUES ('".$part_no."', '".$description."', '".$anouncement."', '".$last_day_sale."', '".$replacement."')");
			if(!$q){
				echo "ERROR: could not execute INSERT query: ".mysqli_error($con);
			} else {
				header('Location: index.php');
			}
		} else {
			echo "Part # and Anouncement cannot be empty";
		}
		
	} else {
		echo "Please fill in al fields!";
	}
} else {

//get total count of entried
$sql = mysqli_query($con, "SELECT COUNT(*) FROM `units`");
if(!$sql){
	echo 'Error: ',mysqli_error();
}
$r = mysqli_fetch_array($sql);

//start pagination
$numrows = $r[0];
$rowsperpage = 30;
$totalpages = ceil($numrows / $rowsperpage);

if(isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
   $currentpage = (int) $_GET['currentpage'];
} else {
   $currentpage = 1;
}

if($currentpage > $totalpages) {
   $currentpage = $totalpages;
}

if($currentpage < 1) {
	$currentpage = 1;
}

$offset = ($currentpage - 1) * $rowsperpage;


// $sql = mysqli_query($con, "SELECT * FROM units ORDER BY id DESC LIMIT $offset, $rowsperpage");
// if(!$sql){
	// echo 'Error: '.mysqli_error();
// }

// test sequence
if(isset($_GET['sort']) && !(empty($_GET['sort']))){
	// do string manipulation on $_GET value and set orderin params in SQL query
	$param = explode('.', $_GET['sort']);
	echo $param[0];
	echo $param[1];
	$sql = mysqli_query($con, "SELECT * FROM units ORDER BY $param[1] $param[0] LIMIT $offset, $rowsperpage");
} else {
	//do a normal query with limit 
	$sql = mysqli_query($con, "SELECT * FROM units ORDER BY id DESC LIMIT $offset, $rowsperpage");
}

// echo gettype($sql);

while($row = mysqli_fetch_assoc($sql)) {
    $id=$row['id'];
    $part_no=$row['part_no'];
    $description=$row['description'];
    $anouncement_date=$row['anouncement_date'];
    $last_day_sale=$row['last_day_sale'];
    $replacement=$row['replacement'];
	echo "<tr>
		 <td>".$part_no."</td>
		 <td>".$description."</td>
		 <td>".$anouncement_date."</td>
		 <td>".$last_day_sale."</td>
		 <td>".$replacement."</td>
		 <td><a href=\"edit.php?id=".$id."\">EDIT</td>
		 <td><a href=\"delete.php?id=".$id."\" class=\"delete\" onClick=\"return confirm('Are you sure you want to delete this record?');\">DELETE</a></td></tr>";
}
?>
</tbody>
</table>
<br />
</div>
<div class="footer">
<center>
<?php
	$range = 3;
	if ($currentpage > 1) {
		   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1'> <input type='button' class='pag' value='<<'> </a> ";
		   $prevpage = $currentpage - 1;
		   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'> <input type='button' class='pag' value='<'> </a> ";
		}

		for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
			if (($x > 0) && ($x <= $totalpages)) {
		    	if ($x == $currentpage) {
		        	echo " <b> <input type='button' class='pag active' value='$x'> </b> ";
		      	} else {
		        	echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x'> <input type='button' class='pag' value='$x'> </a> ";
		      }
		   }
		}
		                 
		if ($currentpage != $totalpages) {
		   $nextpage = $currentpage + 1; 
		   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'> <input type='button' class='pag' value='>'> </a> ";
		   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'> <input type='button' class='pag' value='>>'> </a> ";
		}
}
?>
</center>
</div>
</body>
</html>