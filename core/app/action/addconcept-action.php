<?php

if(count($_POST)>0){
	$user = new ConceptData();
	$user->description = strtoupper($_POST["description"]);
    $user->user_id = $_SESSION['user_id'];
	$user->add();
}

print "<script>window.location='index.php?view=concepts';</script>";

?>