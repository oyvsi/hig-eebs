<?php

class UserModel extends BaseModel {
	protected $userFields = array('userName' => array('table' => 'userName', 'view' => 'Username(*)', 'fieldType' => 'text', 'minLength' => 3, 'maxLength' => 15),
								  'firstName' => array('table' => 'firstName', 'view' => 'Firstname(*)', 'fieldType' => 'text', 'minLength' => 3, 'maxLength' => 100),
								  'lastName' => array('table' => 'lastName', 'view' => 'Lastname(*)', 'fieldType' => 'text', 'minLength' => 3, 'maxLength' => 100),
								  'email' => array('table' => 'email', 'view' => 'Email(*)', 'fieldType' => 'text', 'minLength' => 3, 'maxLength' => 100, 'regex' => '/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/'),
								  'password' => array('table' => 'password', 'view' => 'Password(*)', 'fieldType' => 'password', 'minLength' => 5, 'maxLength' => 100),
								  'password2' => array('table' => 'password2', 'view' => 'Repeat password(*)', 'fieldType' => 'password', 'minLength' => 5, 'maxLength' => 100),
								  'picture' => array('table' => 'picture', 'view' => 'Picture', 'fieldType' => 'file'), 
								  'background' => array('table' => 'background', 'view' => 'Background', 'fieldType' => 'file'));

	/**
	* The different  color themes:
	*/
	protected $themes = array(	'default' => array('value' => 'default', 'view' => 'Default'),
								'northug' => array('value' => 'northug', 'view' => 'Northug'),
								'hellner' => array('value' => 'hellner', 'view' => 'Hellner'));
	/**
	* Default constructor
	*/
	public function __construct() {
		parent::__construct();
	}

	/**
	* function to get the data fields of an user
	* @return array
	*/
	public function getUserFields() {
		return $this->userFields;
	}

	public function getThemes() {
		return $this->themes;
	}

	/**
	* fuction returns a user data based on userName.
	* @param string $userName
	* @return bool|array
	*/
	private function getUser($userName) {
		try { // probably not the way to go...
			$user = $this->getField('userID', $userName);
			
			return $this->fetchUserInfo($user['userID']);
		} catch(Exception $excpt) {
			return false;
		}
	}	

	/**
	* fuction returns users data based on UserID.
	* data is fetched from the database
	* @param int $userID
	* @return array
	*/
	public function fetchUserInfo($userID) {
		$sql = 'SELECT * FROM users WHERE userID = :userID';
		$userInfo = $this->db->selectOne($sql, array(':userID' => $userID));
		if($userInfo === false) {
			throw new Exception('Unable to fetch info for user');
		}

		//gets / sets profile picture info.
		if($userInfo['pictureID'] != null) {
			$sql = 'SELECT * FROM pictures WHERE pictureID = :pictureID';
			$pic = $this->db->selectOne($sql, array('pictureID' => $userInfo['pictureID']));
			$userInfo['profilePicture'] = $pic['url'];

		} else {
			$userInfo['profilePicture'] = __URL_PATH . 'media/images/defaultProfileImage.png';
		}

		//gets background profile url if any.
		if($userInfo['backgroundID'] != null) {
			$sql = 'SELECT * FROM pictures WHERE pictureID = :backgroundID';
			$pic = $this->db->selectOne($sql, array('backgroundID' => $userInfo['backgroundID']));
			$userInfo['backgroundPicture'] = $pic['url'];

		// set this variable to load default background. blablab O-ALF
		} else {
			$userInfo['backgroundPicture'] = '';
		}

		$userInfo['profilePictureThumb'] = ImageUpload::thumbURLfromURL($userInfo['profilePicture']);
		$this->setInfo($userInfo);

		return $userInfo;
	}

	/**
	* fuction returns users data based on Username,
	* and sets user info
	* @param string $userName
	* @return array
	*/
	public function fetchUserProfile($userName) {
		$result = $this->getUser($userName);
		if($result === false) {
			throw new Exception('Unable to fetch info for user');
		}
		
		$this->setInfo($result);
		return $result;
	}
	
	/**
	* gets a given field from database based
	* on a users userName.
	* @param string $field
	* @param string $userName
	* @return array
	*/
	public function getField($field, $userName) {
		$sql = 'SELECT ' . $field . ' FROM users WHERE userName = :userName';

		return $this->db->selectOne($sql, array('userName' => $userName));
	}

	/**
	* fuction returns a picture url based on its ID
	* @param int $pictureID
	* @return string
	*/
	private function getPicture($pictureId) {
		$sql = 'SELECT url FROM pictures WHERE pictureID = :pictureId';
		return $this->db->selectOne($sql, array('pictureId' => $pictureId));
	}

	/**
	* fuction creates a new hashed password
	* and sends it by mail to a user.
	* @param array $params
	*/
	public function forgotPassword($params){

		$userName = $params['userName'];
		$user = $this->getUser($userName);	
		if($user == false) {				
			throw new Exception('No matching username');
		}

		$newPassword = Helpers::generateRandomPassword();
		$sqlInsert = "UPDATE users SET password = :password WHERE userID = :userID";
		$param = array(":password" => Helpers::hashPassword($newPassword), ":userID" => $user['userID']);
		$this->db->insert($sqlInsert, $param);
		//		if($this->db->insert($sqlInsert, $param)) { //TODO: won't work cause insert func will return 0 on update
		$text = 'Hello, ' . $user['firstName'] . '. Your new password for HiG-EEBS is: ' . $newPassword;
		if(!PhpMail::mail($user['email'], 'New password', $text)) {
			throw new Exception('Mail not sent');
		}
	}

	/**
	* fuction validates a login attempt.
	* @param array $userInfo
	* @return bool
	*/
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

	/**
	* fuction updates a users profile picture and returns picture ID.
	* @param sting $file
	* @param string $userName
	* @return int
	*/
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

	/**
	* fuction updates a users background picture and returns picture ID
	* @param sting $file
	* @param string $userName
	* @return int
	*/
	private function updateBackgroundPicture($file, $userName) {
		try {
			$image = new ImageUpload($file, 'backgroundImages');
			$image->setName($userName . '_background');
			$image->setAllowed(array('jpg', 'jpeg', 'png'));
			$image->setMinRes(array('600', '400'));
			$image->setMaxRes(array('1280', '800'));

			$imageFile = $image->process();

			$sql = 'INSERT INTO pictures(url, timestamp) VALUES(:url, :timestamp)';
			$picture = $this->db->insert($sql, array(':url' => $image->getURL(), ':timestamp' => time()));
		} catch(Exception $excpt) {
			throw new Exception($excpt->getMessage());
		}
		return $picture;
	}

	// TODO: Validate form
	/**
	* fuction inserts a new user. 
	* @param array $params
	*/
	public function insertUser($params) {
		$validate = new ValidateForm($params);
		$validate->setRequired($this->userFields);
		if($validate->check() === false) {
			$errors = implode('<br />', $validate->getErrors());
			throw new Exception($errors);
		} else {
		
		extract($params);

		if(isset($_POST['button'])) {
			if(!empty($userName) && !empty($password) && ($password == $password2)) {
				if($this->getUser($userName) === false) {

					$picture = null;
					if(isset($_FILES['picture']['tmp_name']) && is_uploaded_file($_FILES['picture']['tmp_name'])) {
						try {
							$picture = $this->updateProfilePicture($_FILES['picture'], $userName);
						} catch(Exception $excpt) {
							throw new Exception($excpt->getMessage());
						}
					}

					$themePicture = null;
					if(isset($_FILES['background']['tmp_name']) && is_uploaded_file($_FILES['background']['tmp_name'])) {
						try {
							$themePicture = $this->updateBackgroundPicture($_FILES['picture'], $userName);
							//$sql .= ', pictureID = :pictureID';
							//$param += array(':pictureID' => $picture);
							echo "Picture $themePicture";
							die();
						} catch(Exception $excpt) {
							throw new Exception($excpt->getMessage());
						}
					}

					$sql= "INSERT INTO users (userName, firstName, lastName, email, password, pictureID) 
						VALUES (:userName, :firstName, :lastName, :email, :password, :pictureID)";
					$param = array(":userName" => $userName, ":firstName" => $firstName, ":lastName" => $lastName,
						":email" => $email, ":password" => Helpers::hashPassword($password), ':pictureID' => $picture);	

					return $this->db->insert($sql, $param);

				} else {
					throw new Exception('Username ' . $_POST['userName'] . ' already exists');
				}
			} else { 
				echo "ENTER INFO BOY";
			}
			
		}
		}
	}

	/**
	* fuction updates a user.
	* @param array $params
	*/
	public function updateUser($params) {
		$validate = new ValidateForm($params);
		if(empty($params['password'])) {
			array_push($validate->ignoreFields, 'password');
			array_push($validate->ignoreFields, 'password2');
		}
		$validate->setRequired($this->userFields);
		if($validate->check() === false) {
			$errors = implode('<br />', $validate->getErrors());
			throw new Exception($errors);
		} else {
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
					lastName = :lastName, email = :email, theme = :theme";
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

				$themePicture = null;
				if(isset($_FILES['background']['tmp_name']) && is_uploaded_file($_FILES['background']['tmp_name'])) {
					try {
						$themePicture = $this->updateBackgroundPicture($_FILES['background'], $userName);
						$sql .= ', backgroundID = :backgroundID';
						$param += array(':backgroundID' => $themePicture);
					} catch(Exception $excpt) {
						throw new Exception($excpt->getMessage());
					}
				}

				$sql = $sql . " WHERE userName = :loggedIn";
				//				echo $sql;
				$param += array(":userName" => $userName, ":firstName" => $firstName, ":lastName" => $lastName, 
					":email" => $email, ":loggedIn" => $this->userName, ":theme" => $theme);

				//				print_r($param);
				$this->db->insert($sql, $param);


			} else {
				throw new Exception('Username exists');
			}
		} else{
			throw new Exception('Not enough values');
		}
	}
}
}
