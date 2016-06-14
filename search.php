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
<?php
if(!isset($_GET['search'])){
	header('Location: index.php');
}

include('con.php');
$button = $_GET['submit'];
$search = mysqli_real_escape_string($con, filter_var($_GET['search'], FILTER_SANITIZE_STRING));
$message = ''; 
  
if(strlen($search)<=1){
	$message .= "Search term too short";
	?>
	<thead>
			<tr>
				<th colspan="7"><?php echo $message; ?></th>
			</tr>
			<tr>
				<th>Part #</th>
				<th>Description</th>
				<th>Announcement Date</th>
				<th>Last Day of Sale</th>
				<th>Replacement</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
	<?php
} else {
	$message .= "You searched for \"$search\". ";
	$search_exploded = explode (" ", $search);
	 
	$x = "";
	$construct = "";  
	    
	foreach($search_exploded as $search_each){
		$x++;
		if($x==1)
			$construct .="part_no LIKE '%$search_each%' OR description LIKE '%$search_each%'";
		else
			$construct .="AND part_no LIKE '%$search_each%' OR description LIKE '%$search_each%'";
	}
  
	$constructs ="SELECT * FROM units WHERE $construct";
	$run = mysqli_query($con, $constructs);
	    
	$foundnum = mysqli_num_rows($run);
	    
	if ($foundnum == 0){
		$message = "Sorry, there are no matching result for \"<b>$search</b>\".</br>Go <a href=\"index.php\" class=\"grey\">back</a> and try again";
	} else { 
		$message .= "There are $foundnum results matching your criteria!<br />
					 If you wish to go back click <a href=\"index.php\" class=\"grey\">here</a>!";
	}
	?>
	<thead>
		<tr>
			<th colspan="7"><?php echo $message; ?></th>
		</tr>
		<tr>
			<th>Part #</th>
			<th>Description</th>
			<th>Announcement Date</th>
			<th>Last Day of Sale</th>
			<th>Replacement</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<?php
	//start pagination
	$numrows = $foundnum;
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

	$getquery = mysqli_query($con, "SELECT * FROM units WHERE $construct LIMIT $offset, $rowsperpage");
	 
	while($row = mysqli_fetch_assoc($getquery)){
		$id=$row['id'];
		$part_no=$row['part_no'];
	 	$description=$row['description'];
		$anouncement_date=$row['anouncement_date'];
		$last_day_sale=$row['last_day_sale'];
		$replacement=$row['replacement'];
	   
		echo
		"<tr>
			 <td>".$part_no."</td>
			 <td>".$description."</td>
			 <td>".$anouncement_date."</td>
			 <td>".$last_day_sale."</td>
			 <td>".$replacement."</td>
			 <td><a href=\"edit.php?id=".$id."\">EDIT</td>
			 <td><a href=\"delete.php?id=".$id."\" class=\"delete\" onClick=\"return confirm('Are you sure you want to delete this record?');\">DELETE</a></td>
		</tr>";
	}
?>

		<tbody>
		</tbody>
	</table>
	<br />
	</div>
	<div class="footer">
		<center>
		<?php
			$range = 2;
			if ($currentpage > 1) {
				   echo " <a href='search.php?search=$search&submit=Search&currentpage=1'> <input type='button' class='pag' value='<<'> </a> ";
				   $prevpage = $currentpage - 1;
				   echo " <a href='search.php?search=$search&submit=Search&currentpage=$prevpage'> <input type='button' class='pag' value='<'> </a> ";
				}
		
				for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
					if (($x > 0) && ($x <= $totalpages)) {
				    	if ($x == $currentpage) {
				        	echo " <b> <input type='button' class='pag active' value='$x'> </b> ";
				      	} else {
				        	echo " <a href='search.php?search=$search&submit=Search&currentpage=$x'> <input type='button' class='pag' value='$x'> </a> ";
				      }
				   }
				}
				                 
				if ($currentpage != $totalpages) {
				   $nextpage = $currentpage + 1; 
				   echo " <a href='search.php?search=$search&submit=Search&currentpage=$nextpage'> <input type='button' class='pag' value='>'> </a> ";
				   echo " <a href='search.php?search=$search&submit=Search&currentpage=$totalpages'> <input type='button' class='pag' value='>>'> </a> ";
				   //search.php?search=$search&submit=Search&start=$i'
				}
			}
		?>
	</center>
	</div>
</body>
</html>