<?php
$leagues = ['Premier League', 'Bundesliga 1', 'La Liga', 'Serie A', 'Le Championnat'];
$options = [];

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
<div class="container">
	<h1>Soccer Application</h1>
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
			else{
				echo "selected option";
			}
			?>
		</div>
	</div>
</div>
</body>
</html>