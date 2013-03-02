<?php
require 'phpmail/class.phpmailer.php';
require 'phpmail/class.smtp.php';
require 'phpmail/class.pop3.php';

class PhpMail {    
    public static function mail($to, $subject, $text){
	    $mail  = new PHPMailer();   
		$mail->IsSMTP();
		
		//GMAIL config
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure = "ssl";                 // sets the prefix to the server
		$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
		$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
		$mail->Username   = "eebs@svevad.com";  // GMAIL username
		$mail->Password   = "P4ssord1";            // GMAIL password
		//End Gmail
		
		$mail->From       = "eebs@svevad.com";
		$mail->FromName   = "HiG-EEBS";
		$mail->Subject    = $subject;
		$mail->MsgHTML($text);
		
		//$mail->AddReplyTo("reply@email.com","reply name");//they answer here, optional
		$mail->AddAddress($to,"Whom it may concern");
		$mail->IsHTML(true); // send as HTML
		
		return $mail->Send();//returns true if message successfully sent
	    
    }
}
