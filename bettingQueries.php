<?php
$leagues = ['Premier League', 'Bundesliga 1', 'La Liga', 'Serie A', 'Le Championnat'];
$options = ['Upsets'];
$sites = ['bet365', 'Bet&Win'];

$selLeague = isset($_POST['league']) ? $_POST['league'] : '';
$selOption = isset($_POST['option']) ? strtolower($_POST['option']) : '';
$selSort = isset($_POST['sort']) ? $_POST['sort'] : 'most';
$selSite = isset($_POST['site']) ? intval($_POST['site']) : 1;

if($selSite == 1){
	$proSite = "Bet365";
}
else if($selSite == 3){
	$proSite = "Bet&Win";
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
<div class="container" style="height:800px">
	<h1>Soccer Application <img src="ball.jpg" height="42" width="62"></h1>
	<a href="index.php">Aggregate Queries</a> | <a href="avgQueries.php">Average Queries</a> | <a href="bettingQueries.php">Betting Queries</a> | <a href="moreQueries.php">More Queries</a>
	<div class="row">
		<div class="col-md-6" style="float:left;">
		<?php
		echo "<br/><br/>";
		//echo out our form
		echo "<form action=\"bettingQueries.php\" method=\"POST\">";
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
		
		echo "<br/>Betting Site:<br/>";
		echo "<select id=\"site\" name=\"site\" class=\"form-control\" style=\"width: 500px;\">";
		foreach($sites as $site){
			if($site == "bet365"){
				$val = 1;
			}
			else if($site == "Bet&Win"){
				$val = 3;
			}
			echo "<option value=$val>$site</option>";
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
			if($selLeague != '' && (isset($season5) || isset($season4) || isset($season3) || isset($season2) || isset($season1))){
				$title =  "<h3>Showing $selOption from ";
				$title .= ($selLeague == 1) ? "Premier League":  '';
				$title .= ($selLeague == 2) ? "BundesLiga 1":  '';
				$title .= ($selLeague == 3) ? "La Liga":  '';
				$title .= ($selLeague == 4) ? "Serie A":  '';
				$title .= ($selLeague == 5) ? "Le Championnat":  '';
			
				$title .= " (";
				$title .= (isset($season5)) ? "2014/15, " : '';
				$title .= (isset($season4)) ? "2013/14, " : '';
				$title .= (isset($season3)) ? "2012/13, " : '';
				$title .= (isset($season2)) ? "2011/12, " : '';
				$title .= (isset($season1)) ? "2010/11, " : '';
				$title = substr($title, 0, strlen($title) -2);
				$title .= ")";
				$title .= " According to $proSite";
				$title .= "</h3>";
				echo $title;
			}
			
			
			if(!isset($season5) && !isset($season4) && !isset($season3) && !isset($season2) && !isset($season1)){
				echo "Select a Season";
			}			
			else if($selOption == "upsets"){
				$query = "SELECT B.L_id, B.season_id, B.team1 AS team1, B.goals1 AS goals1, B.team2 AS team2, B.goals2 AS goals2, B.H_win_odds AS H_win_odds, B.Draw_odds AS Draw_odds,
						B.A_win_odds AS A_win_odds, date FROM (SELECT * FROM (SELECT t1.L_id, t1.season_id, t1.team_id AS team1, t1.game_id, t1.result AS result1, t1.goals AS goals1, 
						t2.team_id AS team2, t2.result AS result2, t2.goals AS goals2 FROM `teamgame` t1 INNER JOIN `teamgame` t2 ON t1.game_id = t2.game_id WHERE t1.L_id = $selLeague AND (";
				$query .= (isset($season5)) ? "t1.season_id = $season5 OR ": '';
				$query .= (isset($season4)) ? "t1.season_id = $season4 OR ": '';
				$query .= (isset($season3)) ? "t1.season_id = $season3 OR ": '';
				$query .= (isset($season2)) ? "t1.season_id = $season2 OR ": '';
				$query .= (isset($season1)) ? "t1.season_id = $season1 OR ": '';
				$query = substr($query, 0, strlen($query) - 3);		
				$query .= ") AND (t1.home =1 AND t2.home =0)) AS A NATURAL JOIN `gameodds` GO WHERE GO.game_id = A.game_id AND site_id = $selSite 
						AND ((A.result1 = 1 AND H_win_odds > A_win_odds) OR 
						(A.result2 = 1 AND A_win_odds > H_win_odds))) AS B NATURAL JOIN `game` WHERE B.game_id = game_id";
				$result = mysqli_query($con, $query);
				if(!$result){
					echo "query did not work";
				}
				echo "<table class=\"table table-striped\">";
				echo "<thead><th>Date</th><th>Team1 Name</th><th>Team1 Goals</th><th>Team1 Win Odds</th><th>Team2 Name</th><th>Team2 Goals</th><th>Team2 Win Odds</th></thead>";
				echo "<tbody>";
				while($row = mysqli_fetch_assoc($result)){
					//this is kinda a cheat
					$lid = intval($row['L_id']);
					$season_id = intval($row['season_id']);
					$team1_id = intval($row['team1']);
					$team2_id = intval($row['team2']);
					//get team1 name
					$query = "SELECT T_name FROM team WHERE L_id = $lid ANd season_id = $season_id AND T_id = $team1_id;";
					$resultE = mysqli_query($con, $query);
					$data = mysqli_fetch_assoc($resultE);
					$teamName1 = $data['T_name'];
					
					//get team2 name
					$query = "SELECT T_name FROM team WHERE L_id = $lid ANd season_id = $season_id AND T_id = $team2_id;";
					$resultE = mysqli_query($con, $query);
					$data = mysqli_fetch_assoc($resultE);
					$teamName2 = $data['T_name'];
					echo "<tr>";
					echo "<td>" . $row['date'] . "</td><td>" . $teamName1 . "</td><td>" . $row['goals1'] . "</td><td>" . $row['H_win_odds'] . "</td><td>" . $teamName2 . "</td><td>" . $row['goals2'] . "</td>
					<td>" . $row['A_win_odds'] . "</td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";		
			}
			?>
		</div>
	</div>
</div>
<footer class="footer">
    <div class="container">
      <p class="text-muted"><br/><br/><br/>Project by Nathan Moeller, John McGrory</p>
    </div>
</footer>
</body>
</html>