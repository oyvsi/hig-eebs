<?php
/**
 * General helper functions
 */
class Helpers {
	//source: http://stackoverflow.com/questions/1837432/how-to-generate-random-password-with-php
	/**
	* fuction creates a random generated password between 8 and 12 characters.
	* @return string
	*/
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
    
    public static function hashPassword($pwd) {
	    $saltString = "!uF=n34?se._#67";
	    $hashPassword = sha1($pwd . $saltString);
	    return $hashPassword;
    }    

	/**
	 * Creates pretty URL from string
	 * Covertes spaces to underscore and replaces NO-specific characters
	 * everything but alphanumeric is stripped after replace
	 * 
	 * @param String $title the string to convert
	 */
	public static function makePostUrl($title) {
		$title = strtolower($title);
		$replace = array(' ' => '_', "æ" => 'a', "ø" => 'o', "å" => 'a'); // Æ, Ø, Å must be double quoted. Maybe because they're multibyte. Weird!
		foreach($replace as $char => $sub) {
			$title = str_replace($char, $sub, $title);
		}
		$title = preg_replace('/[^0-9a-z_]/', "", $title);

		return $title;
	}

	/**
	* fuction returns the public ip address of a client.
	* @return string
	*/
	public static function getRealIpAddress() {
		 if(!empty($_SERVER['HTTP_CLIENT_IP'])){  
			 $ip=$_SERVER['HTTP_CLIENT_IP']; 

		 } elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
			 $ip=$_SERVER['HTTP_X_FORWARDED_FOR']; 

		 } else { 
			 $ip=$_SERVER['REMOTE_ADDR']; 
		 }
		 return $ip; 
	 }
}
