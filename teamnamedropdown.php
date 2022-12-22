<?php
require_once("steamondb.php");
$coach = $player = $teams = "";

if(isset($_POST["home"])){

  session_destroy();
  session_start;

  header("Location: home.php");
   exit();
}

// query team names for dropdown
$query=
  "SELECT team_id, team_name
  FROM team";

$stmt = $conn->prepare($query);
$stmt->execute();

// loops through the results
foreach ($stmt as $row) {
  $teams .= "<option>{$row["team_name"]}</option>";
}

// checks if the form has been submitted
if (isset($_POST["submit"])) {

  $query1 = "
  SELECT c.coach_id, CONCAT(c.first_name, ' ', c.last_name) AS Coach, c.phone_number AS CPhone, CONCAT(m.first_name, ' ' ,m.last_name) AS Member, m.age, m.league_affiliation, m.start_date, m.phone_number as MPhone
  FROM member m 
  INNER JOIN team_member tm ON m.member_id = tm.member_id
  INNER JOIN team t ON tm.team_id = t.team_id
  INNER JOIN coach c ON c.team_id = t.team_id
  WHERE t.team_name = ?
  GROUP BY c.coach_id
  ORDER BY m.member_id";

  $stmt1 = $conn->prepare($query1);
  $stmt1->execute([ $_POST["team"] ]);

  $query2 = "
  SELECT c.coach_id, CONCAT(c.first_name, ' ', c.last_name) AS Coach, c.phone_number AS CPhone, CONCAT(m.first_name, ' ' ,m.last_name) AS Member, m.age, m.league_affiliation, m.start_date, m.phone_number as MPhone, m.member_id
  FROM member m 
  INNER JOIN team_member tm ON m.member_id = tm.member_id
  INNER JOIN team t ON tm.team_id = t.team_id
  INNER JOIN coach c ON c.team_id = t.team_id
  WHERE t.team_name = ?
  GROUP BY m.member_id
  ORDER BY m.member_id";

  $stmt2 = $conn->prepare($query2);
  $stmt2->execute([ $_POST["team"] ]);

  // loops through each row of the query
  foreach ($stmt1 as $row) {
	$coach .= 
	  "<tr>
      <td>{$row["Coach"]}</td>
	  <td>{$row["CPhone"]}</td>
    </tr>";
  }
   foreach ($stmt2 as $row) {
	$player .=
	 "<tr>
      <td>{$row["Member"]}</td>
	  <td>{$row["MPhone"]}</td>
	  <td>{$row["age"]}</td>
      <td>{$row["league_affiliation"]}</td>
	  <td>{$row["start_date"]}</td>
    </tr>";
  }

  // stores the opening table tag <table> and the header row <tr> with its respective header cells <th> in the results variable
  $coach = 
	"<h3>Coaches for '{$_POST["team"]}':</h3>
  <table class='table table-bordered table-striped'>
    <tr>
	  <th>Coach</th>
	  <th>Coach Phone Number</th>
    </tr>
    {$coach}
  </table>";
  
  $player = 
  "<h3>Roster for '{$_POST["team"]}':</h3>
  <table class='table table-bordered table-striped'>
    <tr>
      <th>Member</th>
	  <th>Player Phone Number</th>
      <th>Age</th>
	  <th>League Affiliation</th>
	  <th>Start Date</th>
    </tr>
    {$player}
  </table>";
}

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
	  </div>
	  <h1>Team Rosters</h1>
	</div>
	<div class="container">
	  <form method="post" class="mb-3">
		<div class="form-group">
		  <select name = "team" class="form-control">
			<option value="" disabled selected>Select a team...</option>
			<?php echo $teams; ?>
		  </select>
		</div>
		<button class="btn name btn-lg" name = "submit" style = "background-color:#b1d572;">Submit</button>
		<button class ="btn name btn-lg" name = "home" style = "background-color:#b1d572;">Go Back Home</button>
		</form>
	  <?php echo $coach; ?>
	  <?php echo $player; ?>
	  </div>
  </body>
<html>															