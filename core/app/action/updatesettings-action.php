<?php
  if(isset($_SESSION["user_id"]) && !empty($_POST)) {
    foreach ($_POST as $p => $k) {
	  if($p == "admin_password") {
		  $password = encrypt($k,"azjkdnyemahdujendchd");
		  if($k != "") ConfigurationData::updateValFromName($p,$password);
	  }
    /*  if($p == "allow_ncf") {
          if(isset($_POST["allow_ncf"])) $allow = 1;
          else $allow = 0;
		  if($k != "") ConfigurationData::updateValFromName($p,$allow);
	  }*/
	  else {
		  ConfigurationData::updateValFromName($p,$k);
	  }
    }

    foreach ($_FILES as $p => $k) {
      if(isset($_FILES[$p])) {
        $image = new Upload($_FILES[$p]);
        if($image->uploaded){
          $image->Process("storage/logos/");
          if($image->processed) {
			 $rootimage = "storage/logos/".$image->file_dst_name;
            ConfigurationData::updateValFromName($p,$rootimage);
          }
        }
      }
    }

    Core::redir("./?view=settings");
  }else{
    Core::redir("./");
  }


  function encrypt($string, $key) {
   $result = '';
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
   }
   return base64_encode($result);
}

?>