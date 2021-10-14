<?php
header('Content-type: application/json');

if(!isset($_SESSION["user_id"])) {
$user = $_POST['username'];
$pass = sha1(md5($_POST['password']));

$base = new Database();
$con = $base->connect();
$sql = "select * from user where (email= \"".$user."\" or username= \"".$user."\") and password= \"".$pass."\" and status=1";
$query = $con->query($sql);
$found = false;
$userid = null;
while($r = $query->fetch_array()){
	$found = true ;
	$userid = $r['id'];
}


if($found==true) {
	//print $userid;
	$_SESSION['user_id']=$userid ;
	$_SESSION['start'] = time(); // Taking now logged in time.
    // Ending a session in 30 minutes from the starting time.
	setcookie('userid',$userid);
	//print "Cargando ... $user";
	//print "<script>window.location='index.php?view=home';</script>";
    $response_array['status'] = 'success';
    $response_array['message'] = 'Acceso Exitoso';
    echo json_encode($response_array);
}else {
	//Core::alert("Usuario o Contraseña Incorrectos");
	//print "<script>window.location='index.php?view=login';</script>";
    $response_array['status'] = 'fail';
    $response_array['message'] = 'Usuario o Contraseña Incorrectos';
    echo json_encode($response_array);
}
}else{
	//print "<script>window.location='index.php?view=home';</script>";
    $response_array['status'] = 'success';
    $response_array['message'] = 'Sesion ya iniciada';
    echo json_encode($response_array);
}


?>