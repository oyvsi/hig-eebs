<?php

class UserModel extends BaseModel {
	protected $userFields = array('password' => array('table' => 'password', 'view' => 'Password', 'fieldType' => 'password', 'minLength' => 5, 'maxLength' => 100),
								  'userName' => array('table' => 'userName', 'view' => 'Username', 'fieldType' => 'text', 'minLength' => 3, 'maxLength' => 15),
								  'firstName' => array('table' => 'firstName', 'view' => 'Firstname', 'fieldType' => 'text', 'minLength' => 3, 'maxLength' => 100),
								  'lastName' => array('table' => 'lastName', 'view' => 'Lastname', 'fieldType' => 'text', 'minLength' => 3, 'maxLength' => 100),
								  'email' => array('table' => 'email', 'view' => 'Email', 'fieldType' => 'text', 'minLength' => 3, 'maxLength' => 100 =),


	public function __construct() {
		parent::__construct();
	}	
	public function fetchUserInfo($userID) {
		$sql = 'SELECT * FROM users WHERE userID = :userID';
		$result = $this->db->selectOne($sql, array('userID' => $userID));
		if($result === false) {
			throw new Exception('Unable to fetch info for user');
		}
		$this->setInfo($result);

		return $result;
	} 
	public function fetchUserProfile($userName) {
		$sql = 'SELECT * FROM users WHERE userName = :userName';
		$result = $this->db->selectOne($sql, array('userName' => $userName));
		if($result === false) {
			throw new Exception('Unable to fetch info for user');
		}
		$this->setInfo($result);

		return $result;
	}	
	public function getUserProfile() {
		return array('userName' => $this->userName, 'firstName' => $this->firstName);
	}

	public function forgotPassword($params){
		if(isset($_POST['submit'])){
			$userName = $params['userName'];
			//echo $userName;
			if(!empty($userName)) {
				$result = $this->fetchUserProfile($userName);	
				if($result == false) {				
					throw new Exception('No matching username');
				}

				$newPassword = Helpers::generateRandomPassword();
				$sqlInsert = "UPDATE users SET password = :password WHERE userID = :userID";
				$param = array(":password" => Helpers::hashPassword($newPassword), ":userID" => $result[0]['userID']);

				if(!$this->db->insert($sqlInsert, $param)){
					$text = 'Hello, ' . $result['firstName'] . '. Your new password for HiG-EEBS is: ' . $newPassword;
					//echo($text);
					if (!PhpMail::mail($result['email'], 'New password', $text)){
						throw new Exception('Mail not sent');
					}
				}
			} else {
				throw new Exception('No username entered');
			}
		}
	}

	public function checkLogin($userInfo) {
		$sql = 'SELECT * from users WHERE userName = :userName AND password = :password';
		$result = $this->db->selectOne($sql, array(':userName' => $userInfo['userName'], 
			':password' => Helpers::hashPassword($_POST['password'])));
		if($result !== false) {
			$this->setInfo($result);
			return true;
		} else {
			throw new Exception('Invalid username or password');
		}
	}


	public function insertUser($params) {
		extract($params);

		if(isset($_POST['button'])) {
			if(!empty($userName) && !empty($password) && ($password == $password2)) {
				$sql = "SELECT * FROM users WHERE userName = :userName"; 
				$result = $this->fetchUserProfile($userName);
				if($result === false) {
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
		extract($params);
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



	public function listUserInfo($username) {
		$lol = $this->db->select("SELECT * FROM users WHERE userName='$username'");
		print($lols[0]['lastName']);

	}


	public function removeUser() {

	}

}
