<?php

// TODO Datenbank abfrage...
// Andere verlinkungen, vllt AJAX
	echo '<tr/>';
for($i = 0; $i < 20; $i++){
	echo '<td><a href="game.php">Gameid='.$i.'</a></td>';
	echo '<td>'.$i.'</td>';
	echo '<tr/>';
}

?>