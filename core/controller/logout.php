<?php
session_start();
// ---
// Eliminar cookies

// -- eliminamos el usuario
if(isset($_SESSION['user_id'])){
	unset($_SESSION['user_id']);
}

session_destroy();

//Volver al index
print "<script>window.location='./';</script>";
?>