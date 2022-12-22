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
SELECT team.team_name, budget.cash_available, SUM(budget.cash_available) AS total
FROM team
INNER JOIN budget ON team.team_id = budget.team_id
GROUP BY team.team_id
ORDER BY total
";

$stmt = $conn -> prepare($query);
$stmt -> execute();

$total = 0;


// concatenate results to $results
foreach ($stmt as $row) {
  $cash = $row["cash_available"];
  $total = $total + $cash;
  
  $results .=
	"<tr>
		<td> {$row["team_name"]}</td>
		<td> {$row["cash_available"]}</td>
	</tr>";
}

// insert results into a table
$results = 
  "<table class = 'table table-striped table-bordered'>
  	<tr>
		<th>Team Name</th>
		<th>Cash Available</th>
  	</tr>
	{$results}
	<tr>
		<td><b>Total</b></td>
		<td><b>$" . number_format($total, 2) . "</b></td>
	</tr>

  </table>";

/*  create, prepare, and execute the query

    CHART PIECE: QUERY COLUMN
    slice labels: label (i.e. Name)
    data slices: col_name (i.e. Count)
*/

$query = "
  SELECT t.team_name, Round((SUM(b.cash_available)/?)*100,2) AS cash_available, t.team_id
  FROM team t
  INNER JOIN budget b ON t.team_id = b.team_id
  GROUP BY t.team_id
  ORDER BY cash_available DESC
  ";
$stmt = $conn->prepare($query);
$stmt->execute([$total]);

// declare the blank arrays
$labels = $data = [];

// loop through each row of the query
foreach ($stmt as $row) {

    // add the slice labels (e.g. Antique, Basic)
    $labels[] = $row["team_name"];

    // add the data slices
    $data[] = $row["cash_available"];
}


// import the chart.js script and create a script to generate the chart with your data
$script =
"<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js'></script>
<script>
new Chart(document.getElementById('pie-chart'), {
    type: 'pie',
    data: {
        labels: " . json_encode($labels) . ",
        datasets: [
            {
			label: '',
            data: " . json_encode($data) . ",

                // sets the background color for each slice of the dataset
             backgroundColor: [
			   'red',
			   'orange',
			   'yellow',
			   'green',
			   'aquamarine',
			   'purple',
			   'black',
			   'pink',
			   'blue',
			   'gray'
              ]

            }
        ]
    },

    // add additional options 
    options: {
        // adds the chart title
        title: {
            display: true,
            text: 'Cash Available By Team'
        }
    }
});
</script>";

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
		<h1>Master Budget</h1>
	  </div>
	</div>
	<form method = "post">
	  <button class = "btn name btn-lg" name = "home" style = "background-color:#b1d572;">Go Back Home</button>
	</form>
	  <canvas id='pie-chart'></canvas>
      <?php echo $script; ?>
	  <div class = "w3-container w3-center" style = "margin-top: 10px">
		<?php echo $results; ?>
	  </div>
  </body>