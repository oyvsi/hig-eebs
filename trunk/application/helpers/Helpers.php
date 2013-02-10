<?php
class Helpers{
	//source: http://stackoverflow.com/questions/1837432/how-to-generate-random-password-with-php
	public static function generateRandomPassword() {  
		//Initialize the random password
		$password = '';
	
		//Initialize a random desired length
		$desired_length = rand(8, 12);
	
		for($length = 0; $length < $desired_length; $length++) {
	    	//Append a random ASCII character (including symbols)
	    	$password .= chr(rand(32, 126));
	    }
	    return $password;
    }    
}

