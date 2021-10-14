<?php

class Post {
	function __get($value){
		if(!$this->exist($value)){
			print "<b>POST ERROR</b> El parámetro <b>$value</b> que intentas llamar no existe!";
			die();
		}
		return $_POST[$value];
	}

	function  exist($value){
		$found = false;
		if(isset($_POST[$value])){
			$found=true;
		}
		return $found;
	}
}



?>