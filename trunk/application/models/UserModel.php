<?php

class UserModel extends BaseModel {
	protected $userFields = array('firstName','lastName', 'email', 'userName', 'password', 'password2');

	public function __construct() {
		parent::__construct();
	}	
	public function fetchUserInfo($userID) {
		$sql = 'SELECT * FROM users WHERE userID = :userID';
		$result = $this->db->select($sql, array('userID' => $userID));
		$this->setInfo($result[0]);

		return $result;
	} 
	public function fetchUserProfile($userName) {
		$sql = 'SELECT * FROM users WHERE userName = :userName';
		$result = $this->db->select($sql, array('userName' => $userName));
		$this->setInfo($result[0]);

		return $result;
	}	
	public function getUserProfile() {
		return array('userName' => $this->userName, 'firstName' => $this->firstName);
	}

	public function forgotPassword($params){
		if(isset($_POST['submit'])){
			$userName = $params['userName'];
			//echo $userName;
			if(!empty($userName)){
				$sql = "SELECT email, firstName, userID FROM users WHERE userName = :userName";
				$result = $this->db->select($sql, array(":userName" => $userName));
				
				if(count($result) != 0){				
					$newPassword = Helpers::generateRandomPassword();
					$sqlInsert = "UPDATE users SET password = :password WHERE userID = :userID";
					$param = array(":password" => Helpers::hashPassword($newPassword), ":userID" => $result[0]['userID']);
					if(!$this->db->insert($sqlInsert, $param)){
						$text = 'Hello, ' . $result[0]['firstName'] . '. Your new password for HiG-EEBS is: ' . $newPassword;
						//echo($text);
						if (!PhpMail::mail($result[0]['email'], 'New password', $text)){
							throw new Exception('Mail not sent');
						}
					}
				} else {
					throw new Exception('No matching username');
				}
			} else {
				throw new Exception('No username entered');
			}
		}
	}

	public function checkLogin($userInfo) {
		$sql = 'SELECT * from users WHERE userName = :userName AND password = :password';
		$result = $this->db->select($sql, array(':userName' => $userInfo['userName'], 
				':password' => Helpers::hashPassword($_POST['password'])));
		if(count($result) == 1) {
			$this->setInfo($result[0]);
			return true;
		} else {
			throw new Exception('Invalid username or password');
		}
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
					$sql= "INSERT INTO users (userName, firstName, email, password) 
						VALUES (:userName, :firstName, :email, :password)";
					$param = array(":userName" => $userName, ":firstName" => $firstName, 
							":email" => $email, ":password" => Helpers::hashPassword($password));	

					$this->db->insert($sql, $param);

				} else {
					echo "Username " . $_POST['userName'] . "exists";
				}


			} else { 
				echo "ENTER INFO BOY";
			}
		}
	}

	public function updateUser($params) {
		$userName = $params['userName'];
		$firstName = $params['firstName'];
		$lastName = $params['lastName'];
		$password = $params['password'];
		$password2 = $params['password2'];
		$email = $params['email'];

		//print_r($params);
		if(isset($_POST['submit'])){
			//BURDE VÆRE EN FUNKSJON SOM KAN SØRGE FOR REQUIRED FILDS, SÅ IFSLØYFA BLIR PENERE, OG DET BLIR MINDRE KODE
			if(!empty($userName) && !empty($firstName) && !empty($lastName)) {
				$param = array();
				$result = array();
				if ($this->userName !== $userName){	//if users has changed userName  
					$sql = "SELECT userID FROM users WHERE userName = :userName";
					$result = $this->db->select($sql, array(":userName" => $userName));
				}
				
				if(count($result) == 0){						//if user changed userName, and didn't exist.
					$sql = "UPDATE users SET userName = :userName, firstName = :firstName, 
						lastName = :lastName, email = :email ";
					if(!empty($password)){
						if($password == $password2){
							$sql = $sql . ", password = :password ";
							$param += array(":password" => Helpers::hashPassword($password));
						} else {
							throw new Exception('Passwords doesn\'t match.');
						}
					} 
					
					$sql = $sql . "WHERE userName = :loggedIn";
					//echo $sql;
					$param += array(":userName" => $userName, ":firstName" => $firstName, ":lastName" => $lastName, 
								":email" => $email, ":loggedIn" => $this->userName);
					//print_r($param);
					$this->db->insert($sql, $param);
				
				} else {
					throw new Exception('Username exists');
				}
			} else{
				throw new Exception('Not enough values');
			}
		}
	}



		public function listUserInfo($username) {
			$lol = $this->db->select("SELECT * FROM users WHERE userName='$username'");
			print($lols[0]['lastName']);

		}


	public function removeUser() {

	}

}
