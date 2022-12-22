<?php
require_once("steamondb.php");

// checks for an existing session ands starts one if there isn't a current session
if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}

if(isset($_POST["roster"])){

  session_destroy();
  session_start;

  header("Location: teamnamedropdown.php");
   exit();
}

if(isset($_POST["budget"])){

  session_destroy();
  session_start;

  header("Location: master-budget.php");
   exit();
}

if(isset($_POST["asset"])){

  session_destroy();
  session_start;

  header("Location: master-assets.php");
   exit();
}








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
	  <div>
		<table>
		  <TR>
		  <td>
			
			</td>
		  </TR>
		</table>
		</div>
	  </div>
	  <h1>Welcome, Administrator</h1>
	</div>
	<div class="container">
	  <form method="post" class="mb-3" style = "text-align: center">
		<div class="form-group">
		</div>
		<nav></nav>
		<button class="btn name btn-lg" name = "roster" style = "background-color:#b1d572;">Master Roster</button>
		<button class="btn name btn-lg" name = "budget" style = "background-color:#b1d572;">Master Budget</button>
		<button class="btn name btn-lg" name = "asset" style = "background-color:#b1d572;">Master Assets</button>
		
		
	  </form>
	  </div>
  </body>
<html>													