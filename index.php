<?php
$leagues = ['Premier League', 'Bundesliga 1', 'La Liga', 'Serie A', 'Le Championnat'];
$options = ['Wins', 'Loses', 'Draws', 'Goals', 'Shots', 'Shots on Target', 'Yellow cards', 'Red Cards', 'Fouls'];

$selLeague = isset($_POST['league']) ? $_POST['league'] : '';
$selOption = isset($_POST['option']) ? $_POST['option'] : '';
$selSort = isset($_POST['sort']) ? $_POST['sort'] : 'most';
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
		<?php
		//echo out our form
		echo "<form action=\"index.php\" method=\"POST\">";
		echo "Select a league: ";
		echo "<select id=\"league\" name=\"league\" class=\"form-control\" style=\"width: 500px;\">";
		foreach($leagues as $league){
			echo "<option value='$league'>$league</option>";
		}
		echo "</select>";
		
				
		echo "<br/>Season:<br/>";
		echo "<label><input type=\"checkbox\" value=\"\"> 2014/2015 </label><br/>";
		echo "<label><input type=\"checkbox\" value=\"\"> 2013/2014 </label><br/>";
		echo "<label><input type=\"checkbox\" value=\"\"> 2012/2013 </label><br/>";
		echo "<label><input type=\"checkbox\" value=\"\"> 2011/2012 </label><br/>";
		echo "<label><input type=\"checkbox\" value=\"\"> 2010/2011 </label><br/>";
		
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
	<div class="row">
		<?php
			echo "League: " . $selLeague . "<br/>";
			echo "Option: " . $selOption . "<br/>";
			echo "Sort by: " . $selSort . "<br/>";
		?>
		<!--
		<table class="table table-striped">
			<thead>
				<th>col1</th>
				<th>col2</th>
				<th>col3</th>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td>2</td>
					<td>3</td>
				</tr>
				<tr>
					<td>4</td>
					<td>5</td>
					<td>6</td>
				</tr>
			</tbody>
		</table>
		-->
	</div>
</div>
</body>
</html>