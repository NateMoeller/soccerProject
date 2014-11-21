<?php
echo "Insert team data...<br/>";

//TODO loop over multiple files?!?

// Create connection
$con=mysqli_connect("localhost","root","", "soccer");

// Check connection
if (mysqli_connect_error()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$leagueId = 1; //change this appropriatly
$seasonId = 2; //change this appropriately

$pathToFile = "data/england/2013_14.csv"; //change this appropriately

$fileHandle = fopen($pathToFile, "r") or die("Unable to open file!");
$count = 0;

$teams;
while(!feof($fileHandle)) {
	$line = fgets($fileHandle);
	if($count == 0){
		//echo $line . "<br/>";
	}
	else if($count > 0){
		if(!$line){
			break; //end of file
		}
		$data = explode(',', $line);
		//var_dump($data);
		$homeTeam = $data[2];
		if(!isset($teams[$homeTeam])){
			$teams[$homeTeam] = $homeTeam;
		}
	}
	$count++;
}
sort($teams);
$teamId = 1;
//insert into the database
foreach ($teams as $key => $value) {
	$query = "INSERT INTO Team (L_id, season_id, T_id, T_name) VALUES ($leagueId, $seasonId, $teamId, '$value');";
	$result = mysqli_query($con, $query);
	if($result){
		echo "Inserted teams data<br/>";
	}
	else{
		echo "TEAM NOT INSERTED<br/>";
	}
	$teamId++;
}

?>