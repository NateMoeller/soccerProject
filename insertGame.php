<?php
echo "Insert game data...<br/>";

// Create connection
$con=mysqli_connect("localhost","root","", "soccer");

// Check connection
if (mysqli_connect_error()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$pathToFile = "data/england/2013_14.csv"; //change this appropriately
$fileHandle = fopen($pathToFile, "r") or die("Unable to open file!");
$count = 0;
$id = 0;

$leagueId = 1; //change this appropriately
$seasonId = 2; //change this appropriately
$homeTeam = '';
$awayTeam = '';

//the below is the indexes that each value appears in the data array (this is hard coded)
//$teamGameAttrib = ['Full Time Home Goals' => 4, 'Full Time Away Goals' => 5, 'Full Time Result' => 6, 'Home Shots' => 11, 'Away Shots' => 12, 'Home Shots on Target' => 13, 
//'Away Shots on Target' => 14, 'Home Corners' => 17, 'Away Corners' => 18, 'Home Fouls' => 15, 'Away Fouls' => 16, 'Home Yellow Cards' => 19, 
//'Away Yellow Cards' => 20, 'Home Red Cards' => 21, 'Away Red Cards' => 22, 'Home Half Goals' => 7, 'Away Half Goals' => 8];


while(!feof($fileHandle)) {
	$line = fgets($fileHandle);
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
			$result = $data[2];
		}
		else if($data[6] == 'A'){
			$result = $data[3];
		}
		else{
			$result = 'Draw';
		}
		$homeTeam = $data[2];
		$awayTeam = $data[3];
		
		
		//insert game
		$query = "INSERT INTO Game (game_id, date, result) VALUES ($id, '$newDate', '$result');";
		$result = mysqli_query($con, $query);
		if($result){
			echo "Inserted game data<br/>";
		}
		else{
			echo "GAME DATA NOT INSERTED<br/>";
		}
		
		//get the game id just inserted
		$gameId = mysqli_insert_id($con);
		
		//HOME TEAM
		$query = "SELECT T_id FROM Team WHERE L_id = $leagueId AND season_id = $seasonId AND T_name = '$homeTeam';";
		$result = mysqli_query($con, $query);
		$returnData = mysqli_fetch_assoc($result);
		$teamId = intval($returnData['T_id']);

		$home = 1;
		$homeGoals = $data[$teamGameAttrib['Full Time Home Goals']];
		$homeShots = $data[$teamGameAttrib['Home Shots']];
		$homeShotsOnTarget = $data[$teamGameAttrib['Home Shots on Target']];
		$homeCorners = $data[$teamGameAttrib['Home Corners']];
		$homeYellows = $data[$teamGameAttrib['Home Yellow Cards']];
		$homeReds = $data[$teamGameAttrib['Home Red Cards']];
		$homeFouls = $data[$teamGameAttrib['Home Fouls']];
		$homeHalfGoals = $data[$teamGameAttrib['Home Half Goals']];
		//woodwork?!?
		//offsides?!?
		
		
		$query = "INSERT INTO `teamgame`(`L_id`, `season_id`, `team_id`, `game_id`, `home`, `goals`, `shots`, `shots_on_target`, `corners`, `yellows`, `reds`, `fouls`, 
		`half_goals`) VALUES ($leagueId, $seasonId, $teamId, $gameId, $home, $homeGoals, $homeShots, $homeShotsOnTarget, $homeCorners, $homeYellows, $homeReds, $homeFouls,
		$homeHalfGoals);";
		$result = mysqli_query($con, $query);
		if($result){
			echo "Inserted Home TeamGame data<br/>";
		}
		else{
			echo "TEAMGAME HOME NOT INSERTED<br/>";
		}
		
		
		//AWAY TEAM
		$query = "SELECT T_id FROM Team WHERE L_id = $leagueId AND season_id = $seasonId AND T_name = '$awayTeam';";
		$result = mysqli_query($con, $query);
		$returnData = mysqli_fetch_assoc($result);
		$teamId = intval($returnData['T_id']);
		
		$home = 0;
		$awayGoals = $data[$teamGameAttrib['Full Time Away Goals']];
		$awayShots = $data[$teamGameAttrib['Away Shots']];
		$awayShotsOnTarget = $data[$teamGameAttrib['Away Shots on Target']];
		$awayCorners = $data[$teamGameAttrib['Away Corners']];
		$awayYellows = $data[$teamGameAttrib['Away Yellow Cards']];
		$awayReds = $data[$teamGameAttrib['Away Red Cards']];
		$awayFouls = $data[$teamGameAttrib['Away Fouls']];
		$awayHalfGoals = $data[$teamGameAttrib['Away Half Goals']];
		//woodwork?!?
		//offsides??!?
		
		
		$query = "INSERT INTO `teamgame`(`L_id`, `season_id`, `team_id`, `game_id`, `home`, `goals`, `shots`, `shots_on_target`, `corners`, `yellows`, `reds`, `fouls`, 
		`half_goals`) VALUES ($leagueId, $seasonId, $teamId, $gameId, $home, $awayGoals, $awayShots, $awayShotsOnTarget, $awayCorners, $awayYellows, $awayReds, $awayFouls,
		$awayHalfGoals);";
		$result = mysqli_query($con, $query);
		if($result){
			echo "Inserted Away TeamGame data<br/>";
		}
		else{
			echo "TEAMGAME AWAY NOT INSERTED<br/>";
		}
		
		
		
		//TODO fix game_id
		//betting sites
		$siteId = 1;
		$hBet365 = round($data[$teamGameAttrib['H Bet365']], 2);
		$dBet365 = round($data[$teamGameAttrib['D Bet365']], 2);
		$aBet365 = round($data[$teamGameAttrib['A Bet365']], 2);
		$query = "INSERT INTO `gameodds`(`game_id`, `site_id`, `H_win_odds`, `Draw_odds`, `A_win_odds`) VALUES ($gameId, $siteId, $hBet365, $dBet365, $aBet365)";
		$result = mysqli_query($con, $query);
		if($result){
			echo "Inserted Bet365 odds data<br/>";
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
			echo "Inserted Bet&Win odds data<br/><br/>";
		}
		else{
			echo "BET&WIN NOT INSERTED<br/>";
		}
		
	}
	$count++;
}
?>