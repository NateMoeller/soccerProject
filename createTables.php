<?php
echo "Creating Tables...<br/><br/>";
$id = 0;
// Create connection
$con=mysqli_connect("localhost","root","", "soccer");

// Check connection
if (mysqli_connect_error()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


//create league table
$query = "CREATE TABLE League(L_id INT NOT NULL AUTO_INCREMENT, ".
       "L_name VARCHAR(32) NOT NULL, ".
       "L_country VARCHAR(32) NOT NULL, ".
       "PRIMARY KEY ( L_id )); ";
$result = mysqli_query($con, $query);
if($result){
	echo "Leage Created Successfully<br/>";
}

$leagues = ['Premier Leage', 'Bundesliga 1', 'La Liga', 'Serie A', 'Le Championnat'];
$countries = ['England', 'Germany', 'Spain', 'Italy', 'France'];
for($i = 0; $i < sizeof($leagues); $i++){
	//insert some league data
	$query = "INSERT INTO League (L_id, L_name, L_country) VALUES ($id, '". $leagues[$i] ."', '".$countries[$i]."');";
	$result = mysqli_query($con, $query);
	if($result){
		echo "Inserted League data<br/>";
	}
}
echo "<br/>";

//create season table
$query = "CREATE TABLE Season(L_id INT NOT NULL, season_id INT NOT NULL, "."year VARCHAR(32) NOT NULL, CONSTRAINT seasonID PRIMARY KEY (L_id, season_id));";
$result = mysqli_query($con, $query);
if($result){
	echo "Season Created Successfully<br/>";
}
//insert season data
$years = ['2010/2011', '2011/2012', '2012/2013', '2013/2014', '2014/2015']; //we can add or change this later
for($i = 1; $i < 6; $i++){
	for($j = 1; $j < 6; $j++){
		$query = "INSERT INTO Season (L_id, season_id, year) VALUES ($i, $j, '".$years[$j-1]."');";
		$result = mysqli_query($con, $query);
		if($result){
			echo "Inserted Season data<br/>";
		}	
	}
}
echo "<br/>";

//create team table
$query = "CREATE TABLE Team(L_id INT NOT NULL, season_id INT NOT NULL, T_id INT NOT NULL, T_name VARCHAR(32) NOT NULL,
	   CONSTRAINT teamID PRIMARY KEY (L_id, season_id, T_id));";
$result = mysqli_query($con, $query);
if($result){
	echo "Team Created Successfully<br/>";
}

//create referee table
$query = "CREATE TABLE Referee(ref_id INT NOT NULL AUTO_INCREMENT, ".
       "ref_name VARCHAR(32) NOT NULL,
	   PRIMARY KEY (ref_id));";
$result = mysqli_query($con, $query);
if($result){
	echo "Referee Created Successfully<br/>";
}

//create game table
//result is the name of the team that won
$query = "CREATE TABLE Game(game_id INT NOT NULL AUTO_INCREMENT, date DATE NOT NULL, result VARCHAR(32) NOT NULL, attendance INT, PRIMARY KEY (game_id));";
$result = mysqli_query($con, $query);
if($result){
	echo "Game Created Successfully<br/>";
}

//create team-game table
$query = "CREATE TABLE TeamGame(L_id INT NOT NULL, season_id INT NOT NULL, team_id INT NOT NULL, game_id INT NOT NULL, home INT NOT NULL, goals INT, shots INT, shots_on_target INT, corners INT, yellows INT, reds INT, fouls INT,
		  half_goals INT, woodwork INT, offsides INT, PRIMARY KEY (L_id, season_id, team_id, game_id));";
$result = mysqli_query($con, $query);
if($result){
	echo "Team-Game Created Successfully<br/>";
}

//create betting site table
$query = "CREATE TABLE BettingSite(site_id INT NOT NULL AUTO_INCREMENT, site_name VARCHAR(32) NOT NULL, site_url VARCHAR(256) NOT NULL, PRIMARY KEY (site_id));";
$result = mysqli_query($con, $query);
if($result){
	echo "BettingSite Created Successfully<br/>";
}
$sites = ['bet365', 'Blue Square', 'Bet&Win', 'Gamebookers', 'Interwetten', 'Ladbrokes', 'Pinnacle', 'Sportingbet', 'Stan James', 'Stanleybet', 
'VC Bet', 'William Hill'];
$urls = ['bet365.com', 'bluesq.com', 'bwin.com', 'sports.gamebookers.com', 'interwetten', 'ladbrokes.com', 'pinnaclesports.com', 'sportingbet.com', 'stanjames.com', 'stanleybet.ro', 
'betvictor.com', 'williamhill.com'];
for($i = 0; $i < sizeof($sites); $i++){
	$query = "INSERT INTO BettingSite (site_id, site_name, site_url) VALUES ($id, '". $sites[$i] ."', '".$urls[$i]."');";
	$result = mysqli_query($con, $query);
	if($result){
		echo "Inserted betting site data<br/>";
	}
}
echo "<br/>";

//create game odds table (DECIMAL may need to be changed to double or float) 
$query = "CREATE TABLE GameOdds(game_id INT NOT NULL, site_id INT NOT NULL, H_win_odds DECIMAL (4, 2) NOT NULL, Draw_odds DECIMAL (4, 2) NOT NULL, A_win_odds DECIMAL (4,2) NOT NULL, PRIMARY KEY (game_id, site_id));";
$result = mysqli_query($con, $query);
if($result){
	echo "GameOdds Created Successfully<br/>";
}




mysqli_close($con);
?>