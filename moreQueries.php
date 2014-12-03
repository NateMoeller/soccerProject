<?php
$leagues = ['Premier League', 'Bundesliga 1', 'La Liga', 'Serie A', 'Le Championnat'];
$options = ['Games which were won after receiving a red card', '% of 1st and 2nd half goals', 'Games lost after scoring 2 or more goals in the first half',
			'Games lost after having more than 50% shots on target', 'Home Win Percentage', 'Away Win Percentage'];

$selLeague = isset($_POST['league']) ? $_POST['league'] : '';
$selOption = isset($_POST['option']) ? strtolower($_POST['option']) : '';
$selSort = isset($_POST['sort']) ? $_POST['sort'] : 'most';

//process option

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
	<a href="index.php">Aggregate Queries</a> | <a href="avgQueries.php">Average Queries</a> | <a href="moreQueries.php">More Queries</a>
	<div class="row">
		<div class="col-md-6" style="float:left;">
		<?php
		echo "<br/><br/>";
		//echo out our form
		echo "<form action=\"moreQueries.php\" method=\"POST\">";
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
				$title .= "</h3>";
				echo $title;
			}
			
			
			if(!isset($season5) && !isset($season4) && !isset($season3) && !isset($season2) && !isset($season1)){
				echo "Select a Season";
			}			
			else if($selOption == "games which were won after receiving a red card"){
				$query = "SELECT A.L_id, A.season_id, A.team1, A.game_id, A.result1, A.goals1, A.reds1, A.team2, A.result2, A.goals2, A.reds2, date FROM
						(SELECT t1.L_id, t1.season_id, t1.team_id AS team1, t1.game_id, t1.result AS result1, t1.goals AS goals1, t1.reds AS reds1, 
						t2.team_id AS team2, t2.result AS result2, t2.goals AS goals2, t2.reds AS reds2
						FROM  `teamgame` t1 INNER JOIN  `teamgame` t2 ON t1.game_id = t2.game_id WHERE t1.L_id = $selLeague
						AND (";
				$query .= (isset($season5)) ? "t1.season_id = $season5 OR ": '';
				$query .= (isset($season4)) ? "t1.season_id = $season4 OR ": '';
				$query .= (isset($season3)) ? "t1.season_id = $season3 OR ": '';
				$query .= (isset($season2)) ? "t1.season_id = $season2 OR ": '';
				$query .= (isset($season1)) ? "t1.season_id = $season1 OR ": '';
				$query = substr($query, 0, strlen($query) - 3);		 
				$query .= ") AND (t1.home =1 AND t2.home =0)AND ((t1.result =1 AND t1.reds > 0)OR (t2.result =1 AND t2.reds >0))
						) AS A
						NATURAL JOIN `game` WHERE game_id = A.game_id;";
				$result = mysqli_query($con, $query);
				if(!$result){
					echo "query did not work";
				}
				echo "<table class=\"table table-striped\">";
				echo "<thead><th>Date</th><th>Team1 Name</th><th>Team1 Goals</th><th>Team1 Reds</th><th>Team2 Name</th><th>Team2 Goals</th><th>Team2 Reds</th></thead>";
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
					echo "<td>" . $row['date'] . "</td><td>" . $teamName1 . "</td><td>" . $row['goals1'] . "</td><td>" . $row['reds1'] . "</td><td>" . $teamName2 . "</td><td>" . $row['goals2'] . "</td>
					<td>" . $row['reds2'] . "</td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
			}
			else if($selOption == "% of 1st and 2nd half goals"){
				$query = "SELECT T_name, SUM(  `half_goals` ) / SUM(  `goals` ) AS FirstHalfPercent, 1 - ( SUM(  `half_goals` ) / SUM(  `goals` ) ) AS SecondHalfPercent
						FROM  `teamgame` NATURAL JOIN  `team` WHERE team_id = T_id AND L_id =$selLeague AND (";
				$query .= (isset($season5)) ? "season_id = $season5 OR ": '';
				$query .= (isset($season4)) ? "season_id = $season4 OR ": '';
				$query .= (isset($season3)) ? "season_id = $season3 OR ": '';
				$query .= (isset($season2)) ? "season_id = $season2 OR ": '';
				$query .= (isset($season1)) ? "season_id = $season1 OR ": '';
				$query = substr($query, 0, strlen($query) - 3);
				$query .= ") GROUP BY  `T_name` ORDER BY T_name ASC";
				
				$result = mysqli_query($con, $query);
				if(!$result){
					echo "query did not work";
				}
				echo "<table class=\"table table-striped\">";
				echo "<thead><th>Team Name</th><th>% of First half Goals</th><th>% of Second half goals</th></thead>";
				echo "<tbody>";
				while($row = mysqli_fetch_assoc($result)){
					echo "<tr>";
					echo "<td>" . $row['T_name'] . "</td><td>" . $row['FirstHalfPercent'] . "</td><td>" . $row['SecondHalfPercent'] . "</td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
			}
			else if($selOption == "games lost after scoring 2 or more goals in the first half"){
				$query = "SELECT A.L_id, A.season_id, A.team1, A.game_id, A.result1, A.goals1, A.half1, A.team2, A.result2, A.goals2, A.half2, date FROM 
						 (SELECT t1.L_id, t1.season_id, t1.team_id AS team1, t1.game_id, t1.result AS result1, t1.goals AS goals1, t1.half_goals AS half1, t2.team_id AS team2, 
						t2.result AS result2, t2.goals AS goals2, t2.half_goals AS half2 FROM `teamgame` t1 INNER JOIN `teamgame` 
						t2 ON t1.game_id = t2.game_id WHERE t1.L_id = $selLeague AND (";
				$query .= (isset($season5)) ? "t1.season_id = $season5 OR ": '';
				$query .= (isset($season4)) ? "t1.season_id = $season4 OR ": '';
				$query .= (isset($season3)) ? "t1.season_id = $season3 OR ": '';
				$query .= (isset($season2)) ? "t1.season_id = $season2 OR ": '';
				$query .= (isset($season1)) ? "t1.season_id = $season1 OR ": '';
				$query = substr($query, 0, strlen($query) - 3);		
				$query .= ") AND (t1.home =1 AND t2.home =0) AND ((t1.result=-1 AND t1.half_goals > 2) OR (t2.result=-1 AND t2.half_goals > 2))) 
						AS A NATURAL JOIN `game` WHERE game_id = A.game_id;";
				$result = mysqli_query($con, $query);
				if(!$result){
					echo "query did not work";
				}
				echo "<table class=\"table table-striped\">";
				echo "<thead><th>Date</th><th>Team1 Name</th><th>Team1 Goals</th><th>Team1 First Half Goals</th><th>Team2 Name</th><th>Team2 Goals</th><th>Team2 First Half Goals</th></thead>";
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
					echo "<td>" . $row['date'] . "</td><td>" . $teamName1 . "</td><td>" . $row['goals1'] . "</td><td>" . $row['half1'] . "</td><td>" . $teamName2 . "</td><td>" . $row['goals2'] . "</td>
					<td>" . $row['half2'] . "</td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";				
			}
			else if($selOption == "games lost after having more than 50% shots on target"){
				$query = "SELECT A.L_id, A.season_id, A.team1, A.game_id, A.result1, A.goals1, (A.ShotsOnTarget1 / A.shots1) AS ShotPercentage1, A.team2, A.result2, A.goals2, 
						(A.ShotsOnTarget2 / A.shots2) AS ShotPercentage2, date FROM 
						(SELECT t1.L_id, t1.season_id, t1.team_id AS team1, t1.game_id, t1.result AS result1, t1.goals AS goals1, t1.shots AS shots1, t1.`shots on target` AS ShotsOnTarget1, 
						t2.team_id AS team2, t2.result AS result2, t2.goals AS goals2, t2.shots AS shots2, t2.`shots on target` AS ShotsOnTarget2 FROM `teamgame` t1 INNER JOIN `teamgame` 
						t2 ON t1.game_id = t2.game_id WHERE t1.L_id = $selLeague AND (";
				$query .= (isset($season5)) ? "t1.season_id = $season5 OR ": '';
				$query .= (isset($season4)) ? "t1.season_id = $season4 OR ": '';
				$query .= (isset($season3)) ? "t1.season_id = $season3 OR ": '';
				$query .= (isset($season2)) ? "t1.season_id = $season2 OR ": '';
				$query .= (isset($season1)) ? "t1.season_id = $season1 OR ": '';
				$query = substr($query, 0, strlen($query) - 3);
				$query .= ") AND (t1.home =1 AND t2.home =0) AND ((t1.result=-1 AND (t1.`shots on target` / t1.`shots`) > .5) 
						OR (t2.result=-1 AND (t2.`shots on target` / t2.`shots`) > .5))) AS A NATURAL JOIN `game` WHERE game_id = A.game_id;";
				$result = mysqli_query($con, $query);
				if(!$result){
					echo "query did not work";
				}
				echo "<table class=\"table table-striped\">";
				echo "<thead><th>Date</th><th>Team1 Name</th><th>Team1 Goals</th><th>Team1 Shots on target %</th><th>Team2 Name</th><th>Team2 Goals</th><th>Team2 Shots on target</th></thead>";
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
					echo "<td>" . $row['date'] . "</td><td>" . $teamName1 . "</td><td>" . $row['goals1'] . "</td><td>" . $row['ShotPercentage1'] . "</td><td>" . $teamName2 . "</td><td>" . $row['goals2'] . "</td>
					<td>" . $row['ShotPercentage2'] . "</td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";		
						
			}
			else if($selOption == "home win percentage"){
				$query = "SELECT A.T_name, A.HomeWins / B.HomeGames AS HomeWinPercentage FROM (
						(SELECT T_name, COUNT(*) AS HomeWins FROM `teamgame` NATURAL JOIN `team` WHERE team_id = T_id AND L_id = $selLeague AND (";
				$query .= (isset($season5)) ? "season_id = $season5 OR ": '';
				$query .= (isset($season4)) ? "season_id = $season4 OR ": '';
				$query .= (isset($season3)) ? "season_id = $season3 OR ": '';
				$query .= (isset($season2)) ? "season_id = $season2 OR ": '';
				$query .= (isset($season1)) ? "season_id = $season1 OR ": '';
				$query = substr($query, 0, strlen($query) - 3);
				$query .= ") AND home = 1 AND result = 1 
						GROUP BY team_id) AS A INNER JOIN
						(SELECT T_name, COUNT(*) AS HomeGames FROM `teamgame` NATURAL JOIN `team` WHERE team_id = T_id AND L_id = $selLeague AND (";
				$query .= (isset($season5)) ? "season_id = $season5 OR ": '';
				$query .= (isset($season4)) ? "season_id = $season4 OR ": '';
				$query .= (isset($season3)) ? "season_id = $season3 OR ": '';
				$query .= (isset($season2)) ? "season_id = $season2 OR ": '';
				$query .= (isset($season1)) ? "season_id = $season1 OR ": '';
				$query = substr($query, 0, strlen($query) - 3);		
				$query .=") AND home = 1 GROUP BY team_id) AS B ON A.`T_name` = B.`T_name`) ORDER BY HomeWinPercentage " . $sort . ";";
				
				$result = mysqli_query($con, $query);
				if(!$result){
					echo "query did not work";
				}
				echo "<table class=\"table table-striped\">";
				echo "<thead><th>Team Name</th><th>Home Win Percentage</th></thead>";
				echo "<tbody>";
				while($row = mysqli_fetch_assoc($result)){
					echo "<tr>";
					echo "<td>" . $row['T_name'] . "</td><td>" . $row['HomeWinPercentage'] . "</td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";							
			}
			else if($selOption == "away win percentage"){
				$query = "SELECT A.T_name, A.AwayWins / B.AwayGames AS AwayWinPercentage FROM (
						(SELECT T_name, COUNT(*) AS AwayWins FROM `teamgame` NATURAL JOIN `team` WHERE team_id = T_id AND L_id = $selLeague AND (";
				$query .= (isset($season5)) ? "season_id = $season5 OR ": '';
				$query .= (isset($season4)) ? "season_id = $season4 OR ": '';
				$query .= (isset($season3)) ? "season_id = $season3 OR ": '';
				$query .= (isset($season2)) ? "season_id = $season2 OR ": '';
				$query .= (isset($season1)) ? "season_id = $season1 OR ": '';
				$query = substr($query, 0, strlen($query) - 3);
				$query .= ") AND home = 0 AND result = 1 
						GROUP BY team_id) AS A INNER JOIN
						(SELECT T_name, COUNT(*) AS AwayGames FROM `teamgame` NATURAL JOIN `team` WHERE team_id = T_id AND L_id = $selLeague AND (";
				$query .= (isset($season5)) ? "season_id = $season5 OR ": '';
				$query .= (isset($season4)) ? "season_id = $season4 OR ": '';
				$query .= (isset($season3)) ? "season_id = $season3 OR ": '';
				$query .= (isset($season2)) ? "season_id = $season2 OR ": '';
				$query .= (isset($season1)) ? "season_id = $season1 OR ": '';
				$query = substr($query, 0, strlen($query) - 3);		
				$query .=") AND home = 0 GROUP BY team_id) AS B ON A.`T_name` = B.`T_name`) ORDER BY AwayWinPercentage " . $sort . ";";
				
				$result = mysqli_query($con, $query);
				if(!$result){
					echo "query did not work";
				}
				echo "<table class=\"table table-striped\">";
				echo "<thead><th>Team Name</th><th>Away Win Percentage</th></thead>";
				echo "<tbody>";
				while($row = mysqli_fetch_assoc($result)){
					echo "<tr>";
					echo "<td>" . $row['T_name'] . "</td><td>" . $row['AwayWinPercentage'] . "</td>";
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