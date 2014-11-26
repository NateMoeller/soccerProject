<?php
$leagues = ['Premier League', 'Bundesliga 1', 'La Liga', 'Serie A', 'Le Championnat'];
$options = ['Wins', 'Loses', 'Draws', 'Goals', 'Shots', 'Shots on Target', '% shots on target', 'Yellow cards', 'Red Cards', 'Fouls'];

$selLeague = isset($_POST['league']) ? $_POST['league'] : '';
$selOption = isset($_POST['option']) ? strtolower($_POST['option']) : '';
$selSort = isset($_POST['sort']) ? $_POST['sort'] : 'most';

//process option
if($selOption == "goals"){
	$proOption = "goals";
}
else if($selOption == "shots"){
	$proOption = "shots";
}
else if($selOption == "shots on target"){
	$proOption = "shots on target";
}
else if($selOption == "yellow cards"){
	$proOption = "yellows";
}
else if($selOption == "red cards"){
	$proOption = "reds";
}
else if($selOption == "fouls"){
	$proOption = "fouls";
}

//process sort
if($selSort == 'most'){
	$sort = "DESC";
}
else{
	$sort = "ASC";
}

if(isset($_POST['2014/15'])){
	$season5 = $_POST['2014/15'];
}
if(isset($_POST['2013/14'])){
	$season4 = $_POST['2013/14'];
}
if(isset($_POST['2012/13'])){
	$season3 = $_POST['2012/13'];
}
if(isset($_POST['2011/12'])){
	$season2 = $_POST['2011/12'];
}
if(isset($_POST['2010/11'])){
	$season1 = $_POST['2010/11'];
}
$con = mysqli_connect("localhost","root","", "soccer");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Soccer Application</title>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<link rel='stylesheet' type='text/css' href='//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>
<script type=\"text/javascript\" src=\"js/jquery-1.10.2.js\"></script>
</head>
<body>
<div class="container">
	<h1>Soccer Application</h1>
	<div class="row">
		<div class="col-md-6" style="float:left;">
		<?php
		//echo out our form
		echo "<form action=\"index.php\" method=\"POST\">";
		echo "Select a league: ";
		echo "<select id=\"league\" name=\"league\" class=\"form-control\" style=\"width: 500px;\">";
		$id = 1;
		foreach($leagues as $league){
			echo "<option value=$id>$league</option>";
			$id++;
		}
		echo "</select>";
		
				
		echo "<br/>Season:<br/>";
		echo "<label class=\"checkbox-inline\"><input name=\"2014/15\" type=\"checkbox\" value=\"5\">2014/2015</label>";
		echo "<label class=\"checkbox-inline\"><input name=\"2013/14\" type=\"checkbox\" value=\"4\">2013/2014</label>";
		echo "<label class=\"checkbox-inline\"><input name=\"2012/13\" type=\"checkbox\" value=\"3\">2012/2013</label>";
		echo "<label class=\"checkbox-inline\"><input name=\"2011/12\" type=\"checkbox\" value=\"2\">2011/2012</label>";
		echo "<label class=\"checkbox-inline\"><input name=\"2010/11\" type=\"checkbox\" value=\"1\">2010/2011</label>";
		
		echo "<br/>Select:"; 
		echo "<select id=\"option\" name=\"option\" class=\"form-control\" style=\"width: 500px;\">";
		foreach($options as $option){
			echo "<option value='$option'>$option</option>";
		}
		echo "</select>";
		
		echo "<br/>Sort by:";
		echo "<select id=\"sort\" name=\"sort\" class=\"form-control\" style=\"width: 500px;\">";
		echo "<option value='most'>Most</option>";
		echo "<option value='least'>Least</option>";
		echo "</select>";
		echo "<br/><br/>";
		
		
		echo "<button type=\"submit\" class=\"btn btn-default\">Submit</button>";
		echo "</form>";
		?>
		</div>
		<div class="col-md-6" style="float:left;">
			<?php
			echo "League: " . $selLeague . "<br/>";
			echo "Option: " . $selOption . "<br/>";
			echo "Sort by: " . $selSort . "<br/><br/>";
			
			
			if(!isset($season5) && !isset($season4) && !isset($season3) && !isset($season2) && !isset($season1)){
				echo "Select a Season";
			}			
			else if($selOption == "goals" || $selOption == "shots" || $selOption == "shots on target" || $selOption == "yellow cards" || $selOption == "red cards" || 
			$selOption == "fouls"){
	
				//SUM query
				$query = "SELECT `T_name`, SUM(`" . $proOption . "`) AS Goals FROM  `teamgame` NATURAL JOIN `team` WHERE team_id = T_id AND L_id = $selLeague AND (";
				$query .= (isset($season5)) ? "season_id = $season5 OR ": '';
				$query .= (isset($season4)) ? "season_id = $season4 OR ": '';
				$query .= (isset($season3)) ? "season_id = $season3 OR ": '';
				$query .= (isset($season2)) ? "season_id = $season2 OR ": '';
				$query .= (isset($season1)) ? "season_id = $season1 OR ": '';
				$query = substr($query, 0, strlen($query) - 3);
				$query .= ") GROUP BY `T_name` ORDER BY Goals " . $sort . ";";
				$result = mysqli_query($con, $query);
				if(!$result){
					echo "query did not work";
				}
				echo "<table class=\"table table-striped\">";
				echo "<thead><th>Team Name</th><th>$selOption</th></thead>";
				echo "<tbody>";
				while($row = mysqli_fetch_assoc($result)){
					echo "<tr>";
					echo "<td>" . $row['T_name'] . "</td><td>" . $row['Goals'] . "</td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
			}
			else if ($selOption == "wins" || $selOption == "loses" || $selOption == "draws"){
				if($selOption == "wins"){
					$gameResult = 1;
				}
				else if ($selOption == "loses"){
					$gameResult = -1;
				}
				else{
					$gameResult = 0;
				}
				
				//SUM query
				$query = "SELECT `T_name`, COUNT(result) AS Result FROM `teamgame` NATURAL JOIN `team` WHERE team_id = T_id AND L_id = $selLeague AND ("; 
				$query .= (isset($season5)) ? "season_id = $season5 OR ": '';
				$query .= (isset($season4)) ? "season_id = $season4 OR ": '';
				$query .= (isset($season3)) ? "season_id = $season3 OR ": '';
				$query .= (isset($season2)) ? "season_id = $season2 OR ": '';
				$query .= (isset($season1)) ? "season_id = $season1 OR ": '';
				$query = substr($query, 0, strlen($query) - 3);
				$query .= ") AND result = $gameResult GROUP BY team_id ORDER BY Result " . $sort . ";";
				$result = mysqli_query($con, $query);
				if(!$result){
					echo "query did not work";
				}
				echo "<table class=\"table table-striped\">";
				echo "<thead><th>Team Name</th><th>$selOption</th></thead>";
				echo "<tbody>";
				while($row = mysqli_fetch_assoc($result)){
					echo "<tr>";
					echo "<td>" . $row['T_name'] . "</td><td>" . $row['Result'] . "</td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
			}
			else if($selOption == "% shots on target"){
				$query = "SELECT `T_name`, SUM(`shots on target`) / SUM(`shots`) AS ShotsPercent FROM `teamgame` NATURAL JOIN `team` WHERE team_id = T_id
				AND L_id = $selLeague AND (";
				$query .= (isset($season5)) ? "season_id = $season5 OR ": '';
				$query .= (isset($season4)) ? "season_id = $season4 OR ": '';
				$query .= (isset($season3)) ? "season_id = $season3 OR ": '';
				$query .= (isset($season2)) ? "season_id = $season2 OR ": '';
				$query .= (isset($season1)) ? "season_id = $season1 OR ": '';
				$query = substr($query, 0, strlen($query) - 3);
				$query .= ") GROUP BY `T_name` ORDER BY ShotsPercent " . $sort . ";";
				$result = mysqli_query($con, $query);
				if(!$result){
					echo "query did not work";
				}
				echo "<table class=\"table table-striped\">";
				echo "<thead><th>Team Name</th><th>$selOption</th></thead>";
				echo "<tbody>";
				while($row = mysqli_fetch_assoc($result)){
					echo "<tr>";
					echo "<td>" . $row['T_name'] . "</td><td>" . $row['ShotsPercent'] . "</td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
			}
			?>
		</div>
	</div>
</div>
</body>
</html>