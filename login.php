<?php
require_once("steamondb.php");
$message = "";

if(isset($_POST["submit"])){
  
  $username = $_POST["username"];
  $password = hash("md5", $_POST["password"]);
  
  $query = "
  SELECT username, password
  FROM administrator
  WHERE username = ?
  AND password = ?
  ";
  
  $stmt = $conn->prepare($query);
  $stmt->execute([$username, $password]);
  
  if($stmt->rowCount() > 0){
	
	session_destroy();
	session_start;
	
	$_SESSION["user"] = $username;
	
	header("Location: home.php");
	exit();
  } 
  else {
	$message = "<div class = w3-red>Your login credentials are incorrect!</div>";
  }
}

$conn = null
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
	<div class="w3-container w3-center" style = "background-color: #b1d572">
	  <div id = "logo" class = "w3-container" style = "justify-content:start">
	  	<img src = "logo.png" style = "width200:px; height:80px; justify-content:start;">
	  </div>
	  <h1>
		Log in
	  </h1>
	</div>
	<div class="w3-container w3-center">
	  <form method = "post">
		<div class = "w3-row-padding w3-center" style="margin-top:14px">
		  <input name = "username" type = "text" placeholder="Enter your username">
		</div>
		
		<div class = "w3-row-padding w3-center" style="margin-top:14px">
		  <input name = "password" type = "password" placeholder="Enter your password">
		</div>
		
		<div class = "w3-row-padding w3-center" style="margin-top:14px">
			<button class = "btn name btn-lg" name = "submit" style = "background-color:#b1d572;">Log in</button>
		  <?php echo $message?>
		</div>
	  </form>
	</div> 
  </body>
</html>