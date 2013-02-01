<?php

class UserModel extends BaseModel {

	public function __construct() {
		parent::__construct();
		

	}	
	
	public function insertUser($params) {
 		$userName = $params['userName'];
 		$firstName = $params['firstName'];
 		$lastName = $params['lastName'];
 		$password = $params['password'];
 		$password2 = $params['password2'];
 		$email = $params['email'];

 		print_r($params);



  if(isset($_POST['button'])) {
  	echo "POST OK";
   	if(!empty($userName) && !empty($password) && ($password == $password2)) {
 		echo "ENTERED IF USERNAME";
 		 $sql = "SELECT * FROM users WHERE userName = :userName"; 
     	$result = $this->db->select($sql, array(':userName' => $params['userName']));
 		print($result);
    	if(!$result) {
        	echo "DIDNT EXIST username";
        	$sql= "INSERT INTO users (userName, firstName, email) 
          			   VALUES (:userName, :firstName, :email)";
         	$param = array(":userName" => $userName, ":firstName" => $firstName, ":email" => $email);	

         	$this->db->insert($sql, $param);

         } else {
         	echo "Username " . $_POST['userName'] . "exists";
		 }
  		
   
   } else { 
   	 echo "ENTER INFO BOY";
   }
}
}

	public function updateUser() {}


	


	public function listUserInfo($username) {
		$lol = $this->db->select("SELECT * FROM users WHERE userName='$username'");
		print($lols[0]['lastName']);

	}


	public function removeUser() {

	}

}
