<?php
echo "Insert game data...<br/>";

// Create connection
$con=mysqli_connect("localhost","root","", "soccer");

// Check connection
if (mysqli_connect_error()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$path = "data/france";
$leagueId = 5; //change this appropriately
$seasonId = 1;
$id = 0;
// Open the folder 
$dir_handle = @opendir($path) or die("Unable to open $path");
$files = scandir($path);
while ($file = readdir($dir_handle)) {
	if($file != '.' && $file != '..'){
		$pathToFile = $path . "/" . $file;
		$fileHandle = fopen($pathToFile, "r") or die("Unable to open file!");
		$count = 0;

		
		while(!feof($fileHandle)) {
			$line = fgets($fileHandle);
			mysqli_autocommit($con, FALSE);
			if($count == 0){
				echo $line . "<br/>";
				$header = explode(",", $line);
				for($i = 0; $i < sizeof($header); $i++){
					//get the index of each attribute
					switch($header[$i]){
						case 'FTHG':
							$teamGameAttrib['Full Time Home Goals'] = $i;
							break;
						case 'FTAG':
							$teamGameAttrib['Full Time Away Goals'] = $i;
							break;
						case 'FTR':
							$teamGameAttrib['Full Time Result'] = $i;
							break;
						case 'HS':
							$teamGameAttrib['Home Shots'] = $i;
							break;
						case 'AS':
							$teamGameAttrib['Away Shots'] = $i;
							break;
						case 'HST':
							$teamGameAttrib['Home Shots on Target'] = $i;
							break;
						case 'AST':
							$teamGameAttrib['Away Shots on Target'] = $i;
							break;
						case 'HC':
							$teamGameAttrib['Home Corners'] = $i;
							break;
						case 'AC':
							$teamGameAttrib['Away Corners'] = $i;
							break;
						case 'HF':
							$teamGameAttrib['Home Fouls'] = $i;
							break;
						case 'AF':
							$teamGameAttrib['Away Fouls'] = $i;
							break;
						case 'HY':
							$teamGameAttrib['Home Yellow Cards'] = $i;
							break;
						case 'AY':
							$teamGameAttrib['Away Yellow Cards'] = $i;
							break;	
						case 'HR':
							$teamGameAttrib['Home Red Cards'] = $i;
							break;
						case 'AR':
							$teamGameAttrib['Away Red Cards'] = $i;
							break;
						case 'HTHG':
							$teamGameAttrib['Home Half Goals'] = $i;
							break;
						case 'HTAG':
							$teamGameAttrib['Away Half Goals'] = $i;
							break;
						case 'B365H':
							$teamGameAttrib['H Bet365'] = $i;
							break;
						case 'B365D':
							$teamGameAttrib['D Bet365'] = $i;
							break;
						case 'B365A':
							$teamGameAttrib['A Bet365'] = $i;
							break;
						case 'BWH':
							$teamGameAttrib['H Bet&Win'] = $i;
							break;
						case 'BWD':
							$teamGameAttrib['D Bet&Win'] = $i;
							break;
						case 'BWA':
							$teamGameAttrib['A Bet&Win'] = $i;
							break;	
					}
				}
			}
			else if($count > 0){
				if(!$line){
					break; //end of file
				}
				$data = explode(',', $line);
				$date = $data[1];
				$dateElements = explode('/', $date);
				if($dateElements[2] == "14"){
					$year = "20" . $dateElements[2];
				}
				else{
					$year = $dateElements[2];
				}
				$newDate = $year . "-" . $dateElements[1] . "-" . $dateElements[0];
				if($data[6] == 'H'){
					$gameResult = mysqli_real_escape_string($con, $data[2]);
					$homeWin = 1;
					$awayWin = -1;
				}
				else if($data[6] == 'A'){
					$gameResult = mysqli_real_escape_string($con, $data[3]);
					$homeWin = -1;
					$awayWin = 1;
				}
				else{
					$gameResult = 'Draw';
					$homeWin = 0;
					$awayWin = 0;
				}
				$homeTeam = mysqli_real_escape_string($con, $data[2]);
				$awayTeam = mysqli_real_escape_string($con, $data[3]);
		
				//HOME TEAM id
				$query = "SELECT T_id FROM Team WHERE L_id = $leagueId AND season_id = $seasonId AND T_name = '$homeTeam';";
				$result = mysqli_query($con, $query);
				$returnData = mysqli_fetch_assoc($result);
				$homeTeamId = intval($returnData['T_id']);
				
				//AWAY TEAM id
				$query = "SELECT T_id FROM Team WHERE L_id = $leagueId AND season_id = $seasonId AND T_name = '$awayTeam';";
				$result = mysqli_query($con, $query);
				$returnData = mysqli_fetch_assoc($result);
				$awayTeamId = intval($returnData['T_id']);
				
				//insert game
				$query = "INSERT INTO `game`(`game_id`, `date`, `result`, `H_team`, `A_team`) VALUES (" . $id . ", '" . $newDate . "', '" . $gameResult . "', " . $homeTeamId . ", " . $awayTeamId . ")";
				$result = mysqli_query($con, $query);
				if($result){
					//echo "Inserted game data<br/>";
				}
				else{
					echo "GAME DATA NOT INSERTED<br/>";
				}
				
				//get the game id just inserted
				$gameId = mysqli_insert_id($con);
			
				//HOME TEAM
				$home = 1;
				$homeGoals = ($data[$teamGameAttrib['Full Time Home Goals']] != '') ? $data[$teamGameAttrib['Full Time Home Goals']] : "NULL";
				$homeShots = ($data[$teamGameAttrib['Home Shots']] != '') ? $data[$teamGameAttrib['Home Shots']] : "NULL";
				$homeShotsOnTarget = ($data[$teamGameAttrib['Home Shots on Target']] != '') ? $data[$teamGameAttrib['Home Shots on Target']] : "NULL";
				$homeCorners = ($data[$teamGameAttrib['Home Corners']] != '') ? $data[$teamGameAttrib['Home Corners']] : "NULL";
				$homeYellows = ($data[$teamGameAttrib['Home Yellow Cards']] != '') ? $data[$teamGameAttrib['Home Yellow Cards']] : "NULL";
				$homeReds = ($data[$teamGameAttrib['Home Red Cards']] != '') ? $data[$teamGameAttrib['Home Red Cards']] : "NULL";
				$homeFouls = ($data[$teamGameAttrib['Home Fouls']] != '') ? $data[$teamGameAttrib['Home Fouls']] : "NULL";
				$homeHalfGoals = ($data[$teamGameAttrib['Home Half Goals']] != '') ? $data[$teamGameAttrib['Home Half Goals']] : "NULL";
				//woodwork?!?
				//offsides?!?
		
				
				$query = "INSERT INTO `teamgame`(`L_id`, `season_id`, `team_id`, `game_id`, `result`, `home`, `goals`, `shots`, `shots on target`, `corners`, `yellows`, `reds`, `fouls`, 
				`half_goals`) VALUES ($leagueId, $seasonId, $homeTeamId, $gameId, $homeWin, $home, $homeGoals, $homeShots, $homeShotsOnTarget, $homeCorners, $homeYellows, $homeReds, $homeFouls,
				$homeHalfGoals);";
				$result = mysqli_query($con, $query);
				if($result){
					//echo "Inserted Home TeamGame data<br/>";
				}
				else{
					echo "TEAMGAME HOME NOT INSERTED<br/>";
					echo $query . "<br/>";
					var_dump($homeShots);
				}
				
		
				//AWAY TEAM
		
				$home = 0;
				$awayGoals = ($data[$teamGameAttrib['Full Time Away Goals']] != '') ? $data[$teamGameAttrib['Full Time Away Goals']] : "NULL";
				$awayShots = ($data[$teamGameAttrib['Away Shots']] != '') ? $data[$teamGameAttrib['Away Shots']] : "NULL";
				$awayShotsOnTarget = ($data[$teamGameAttrib['Away Shots on Target']] != '') ? $data[$teamGameAttrib['Away Shots on Target']] : "NULL";
				$awayCorners = ($data[$teamGameAttrib['Away Corners']] != '') ? $data[$teamGameAttrib['Away Corners']] : "NULL";
				$awayYellows = ($data[$teamGameAttrib['Away Yellow Cards']] != '') ? $data[$teamGameAttrib['Away Yellow Cards']] : "NULL";
				$awayReds = ($data[$teamGameAttrib['Away Red Cards']] != '') ? $data[$teamGameAttrib['Away Red Cards']] : "NULL";
				$awayFouls = ($data[$teamGameAttrib['Away Fouls']] != '') ? $data[$teamGameAttrib['Away Fouls']] : "NULL";
				$awayHalfGoals = ($data[$teamGameAttrib['Away Half Goals']] != '') ? $data[$teamGameAttrib['Away Half Goals']] : "NULL";
				//woodwork?!?
				//offsides??!?
		
				
				$query = "INSERT INTO `teamgame`(`L_id`, `season_id`, `team_id`, `game_id`, `result`, `home`, `goals`, `shots`, `shots on target`, `corners`, `yellows`, `reds`, `fouls`, 
				`half_goals`) VALUES ($leagueId, $seasonId, $awayTeamId, $gameId, $awayWin, $home, $awayGoals, $awayShots, $awayShotsOnTarget, $awayCorners, $awayYellows, $awayReds, $awayFouls,
				$awayHalfGoals);";
				$result = mysqli_query($con, $query);
				if($result){
					//echo "Inserted Away TeamGame data<br/>";
				}
				else{
					echo "TEAMGAME AWAY NOT INSERTED<br/>";
					echo $query . "<br/>";
					var_dump($awayShots);
				}
				
		
				//betting sites
				$siteId = 1;
				$hBet365 = round($data[$teamGameAttrib['H Bet365']], 2);
				$dBet365 = round($data[$teamGameAttrib['D Bet365']], 2);
				$aBet365 = round($data[$teamGameAttrib['A Bet365']], 2);
				$query = "INSERT INTO `gameodds`(`game_id`, `site_id`, `H_win_odds`, `Draw_odds`, `A_win_odds`) VALUES ($gameId, $siteId, $hBet365, $dBet365, $aBet365)";
				$result = mysqli_query($con, $query);
				if($result){
					//echo "Inserted Bet365 odds data<br/>";
				}
				else{
					echo "BET365 NOT INSERTED<br/>";
				}		
		
		
		
				$siteId = 3;
				$hBetWin = round($data[$teamGameAttrib['H Bet&Win']], 2);
				$dBetWin = round($data[$teamGameAttrib['D Bet&Win']], 2);
				$aBetWin = round($data[$teamGameAttrib['A Bet&Win']], 2);
				
				
				$query = "INSERT INTO `gameodds`(`game_id`, `site_id`, `H_win_odds`, `Draw_odds`, `A_win_odds`) VALUES ($gameId, $siteId, $hBetWin, $dBetWin, $aBetWin)";
				$result = mysqli_query($con, $query);
				if($result){
					//echo "Inserted Bet&Win odds data<br/><br/><br/>";
				}
				else{
					echo "BET&WIN NOT INSERTED<br/><br/><br/>";
				}
				
			}
			$count++;
		}
		mysqli_commit($con);
		$seasonId++;
	}
}




?>