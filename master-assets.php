<?php
require_once("steamondb.php");
$results = "";

if(isset($_POST["home"])){

  session_destroy();
  session_start;

  header("Location: home.php");
   exit();
}


$query = "
SELECT team_name, asset_name, instock
FROM asset
INNER JOIN team USING(team_id)
GROUP BY team_name, asset_name
ORDER BY team_name
";

$stmt = $conn -> prepare($query);
$stmt -> execute();


foreach ($stmt as $row) {
 
  
  
  
  $results .=
	"<tr>
		<td> {$row["team_name"]}</td>
		<td> {$row["asset_name"]}</td>
		<td> {$row["instock"]}</td>
	</tr>";

}
// insert results into a table
$results = 
  "<table class = 'table table-striped table-bordered'>
  	<tr>
		<th>Team Name</th>
		<th>Asset Name</th>
		<th>In Stock</th>
  	</tr>
	{$results}
	<tr>
		
	</tr>

  </table>";







$conn = null;
?>

<!doctype html>
<html>
  <head>
	<link rel = "stylesheet"
		  href = "https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet"
		  href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.css">
  </head>
  <body>
	<div class="jumbotron text-center" style = "background-color: #b1d572">
	  <div class="w3-container w3-center" style = "background-color: #b1d572">
		<div id = "logo" class = "w3-container" style = "justify-content:start">
		  <img src = "logo.png" style = "width200:px; height:80px; justify-content:start;">
		</div>
		<h1>Master Assets</h1>
	  </div>
	</div>
	<form method = "post">
	  <button class ="btn name btn-lg" name = "home" style = "background-color:#b1d572;">Go Back Home</button>
	</form>
	<div class = "w3-container w3-center" style = "margin-top: 10px">
	  <?php echo $results; ?>
	</div>
  </body>
  </head