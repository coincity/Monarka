<?php
class MailData {
	public static $tablename = "";
	public $mail = null;

	public function open(){

    $this->mail = new PHPMailer();
	
	$password = '';
	$string = ConfigurationData::getByPreffix("admin_password")->val;
	$key = 'azjkdnyemahdujendchd';
	$string = base64_decode($string);
	for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
	    $char = chr(ord($char)-ord($keychar));
    	$password.=$char;
	}

    // ---------- adjust these lines ---------------------------------------
    $email_user = ConfigurationData::getByPreffix("admin_email")->val;
    $email_password = $password;
    $this->mail->Username = $email_user; // your GMail user name
    $this->mail->Password = $email_password; 
    //-----------------------------------------------------------------------
	//$this->mail->IsSMTP(); // use SMTP
	$this->mail->Host = "smtp.gmail.com"; // GMail
	$this->mail->SMTPAuth = true; // turn on SMTP authentication
	$this->mail->SMTPSecure = 'ssl';
	$this->mail->Port = 465;
	$this->mail->setFrom($this->mail->Username);
	$this->mail->isHTML(true);
	$this->mail->CharSet="windows-1251";
	$this->mail->CharSet="utf-8";

    
    

    $this->mail->From = $this->mail->Username;
	}

	public  function RegisterSuccess(){
    $this->mail->Subject = "Registro Exitoso";
    $this->mail->Body    = "Se ha creado tu cuenta en el sistema de coaching."; 
	}
	public function send(){
        if(Core::$send_alert_emails){
        //$this->mail->AddAddress(ConfigurationData::getByPreffix("admin_email")->val); // recipients email
        $this->mail->Body .="<h1 style='color:#3498db;'>".ConfigurationData::getByPreffix("company_name")->val."</h1>";
        $this->mail->Body .= "<p>$this->message</p>";
        //$this->mail->Body .= "<p>Usuario: ".Core::$user->name." ".Core::$user->lastname."</p>";
        //$this->mail->Body .= "<p>Fecha y Hora: ".date("d-m-Y h:i:s")."</p>";
        //$this->mail->Body .= "<p><i>".Core::$email_footer."</i></p>";
	    
			if(!$this->mail->send()) 
			{
				Core::alert("Mailer Error: " . $this->mail->ErrorInfo);
			} 
			else 
			{
				Core::alert("Message has been sent successfully");
			}
        }
	}

}

?>