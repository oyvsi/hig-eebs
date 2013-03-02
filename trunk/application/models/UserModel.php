<?php

class UserModel extends BaseModel {
	protected $userFields = array('userName' => array('table' => 'userName', 'view' => 'Username', 'fieldType' => 'text', 'minLength' => 3, 'maxLength' => 15),
								  'firstName' => array('table' => 'firstName', 'view' => 'Firstname', 'fieldType' => 'text', 'minLength' => 3, 'maxLength' => 100),
								  'lastName' => array('table' => 'lastName', 'view' => 'Lastname', 'fieldType' => 'text', 'minLength' => 3, 'maxLength' => 100),
								  'email' => array('table' => 'email', 'view' => 'Email', 'fieldType' => 'text', 'minLength' => 3, 'maxLength' => 100),
								  'password' => array('table' => 'password', 'view' => 'Password', 'fieldType' => 'password', 'minLength' => 5, 'maxLength' => 100),
								  'password2' => array('table' => 'password2', 'view' => 'Repeat password', 'fieldType' => 'password', 'minLength' => 5, 'maxLength' => 100),
								  'picture' => array('table' => 'picture', 'view' => 'Picture', 'fieldType' => 'file'));

	public function __construct() {
		parent::__construct();
	}

	public function getUserFields() {
		return $this->userFields;
	}

	public function fetchUserInfo($userID) {
		$sql = 'SELECT * FROM users WHERE userID = :userID';
		$userInfo = $this->db->selectOne($sql, array('userID' => $userID));
		if($userInfo === false) {
			throw new Exception('Unable to fetch info for user');
		}
		if($userInfo['pictureID']) {
			$sql = 'SELECT * FROM pictures WHERE pictureID = :pictureID';
			$pic = $this->db->selectOne($sql, array(':pictureID' => $userInfo['pictureID']));
			$userInfo['pictureURL'] = $pic['url'];
		} else {
			$userInfo['pictureURL'] = null;
		}
		$this->setInfo($userInfo);

		return $userInfo;
	} 
	public function fetchUserProfile($userName) {
		$result = $this->getUser($userName);
		if($result === false) {
			throw new Exception('Unable to fetch info for user');
	}
		$this->setInfo($result);

		return $result;
	}

	private function getUser($userName) {
		$sql = 'SELECT * FROM users WHERE userName = :userName';
		return $this->db->selectOne($sql, array('userName' => $userName));
	}	

	public function getUserProfile() {
		return array('userName' => $this->userName, 'firstName' => $this->firstName);
	}

	public function forgotPassword($params){
		
		$userName = $params['userName'];
		$user = $this->getUser($userName);	
		if($user == false) {				
			throw new Exception('No matching username');
		}

		$newPassword = Helpers::generateRandomPassword();
		$sqlInsert = "UPDATE users SET password = :password WHERE userID = :userID";
		$param = array(":password" => Helpers::hashPassword($newPassword), ":userID" => $user['userID']);
//		if($this->db->insert($sqlInsert, $param)) { //TODO: won't work cause insert func will return 0 on update
			$text = 'Hello, ' . $user['firstName'] . '. Your new password for HiG-EEBS is: ' . $newPassword;
			if(!PhpMail::mail($user['email'], 'New password', $text)) {
				throw new Exception('Mail not sent');
			}
//		} else {
//			echo "error";
//		}
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

	private function updateProfilePicture($file, $userName) {
		try {
			$image = new ImageUpload($_FILES['picture'], 'profileImages');
			$image->setName($userName);
			$image->setAllowed(array('jpg', 'jpeg', 'png'));
			$image->setMinRes(array('100', '100'));
			$image->setMaxRes(array('1280', '800'));

			$imageFile = $image->process();
			$imageThumb = $image->genThumb();

			$sql = 'INSERT INTO pictures(url, timestamp) VALUES(:url, :timestamp)';
			$picture = $this->db->insert($sql, array(':url' => $image->getURL(), ':timestamp' => time()));
		} catch(Exception $excpt) {
			throw new Exception($excpt->getMessage());
		}

		return $picture;
	}


	// TODO: Validate form
	public function insertUser($params) {
		extract($params);

		if(isset($_POST['button'])) {
			if(!empty($userName) && !empty($password) && ($password == $password2)) {
				if($this->getUser($userName) === false) {

					$picture = null;
					if(!empty($_FILES['picture'])) {
						try {
							$picture = $this->updateProfilePicture($_FILES['picture'], $userName);
						} catch(Exception $excpt) {
							throw new Exception($excpt->getMessage());
						}
					}

					$sql= "INSERT INTO users (userName, firstName, email, password, pictureID) 
						VALUES (:userName, :firstName, :email, :password, :pictureID)";
					$param = array(":userName" => $userName, ":firstName" => $firstName, 
						":email" => $email, ":password" => Helpers::hashPassword($password), ':pictureID' => $picture);	

					$this->db->insert($sql, $param);

				} else {
					throw new Exception('Username ' . $_POST['userName'] . ' already exists');
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
			$result = false;

			if($this->userName !== $userName) {	//if users has changed userName  
				$result = $this->getUser($userName);
			}

			if($result === false){						//if user changed userName, and didn't exist.
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

				$picture = null;
				if(isset($_FILES['picture']['tmp_name']) && is_uploaded_file($_FILES['picture']['tmp_name'])) {
					try {
						$picture = $this->updateProfilePicture($_FILES['picture'], $userName);
						$sql .= ', pictureID = :pictureID';
						$param += array(':pictureID' => $picture);
					} catch(Exception $excpt) {
						throw new Exception($excpt->getMessage());
					}
				}

				$sql = $sql . " WHERE userName = :loggedIn";
				//				echo $sql;
				$param += array(":userName" => $userName, ":firstName" => $firstName, ":lastName" => $lastName, 
					":email" => $email, ":loggedIn" => $this->userName);
				//				print_r($param);
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
