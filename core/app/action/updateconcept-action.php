<?php
if(count($_POST)>0){
	$user = ConceptData::getById($_POST["concept_id"]);
	$user->description = strtoupper($_POST["description"]);
    $user->user_id = $_SESSION['user_id'];
	$user->update();
}

print "<script>window.location='index.php?view=concepts';</script>";


?>