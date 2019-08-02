<?php
//require_once '../res/php/helpers.php';
//redirect_index();
?>

<body id="body">
    LOBBY!!1!
	
	<alert>
	<?php
	if(isset($_POST['remove'])){
		//remove in Table id where userid = hostid
	}
	if(isset($_POST['create'])){

		echo '<script language="Javascript">';
		echo 'alert("Spiel erstellt"); ';
		echo ' </script>';
	}
	else{
		
		echo '<script language="Javascript">';
		echo 'alert("not hot"); ';
		echo ' </script>';
		
	}
	?>

</body>
</html>
