<?php
class ChatAuthModel extends BaseModel{

	private $expired_time = 600; //10 minutes
	private $table_name="chatauth";
	public function __construct(){
		 parent::__construct();
	}

	public function generateToken($userId){

		$token = uniqid("",true);
		$this->saveToken($userId,$token);

		return $token;
	}

	private function saveToken($userId,$token){

		$expired_at = time()+$this->expired_time;
		try{

			$sql = "INSERT INTO $this->table_name SET token=PASSWORD(?),userId=?,expired_at=?";

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("sss", $token ,$userId, $expired_at);
		    $stmt->execute();
		    $result = $stmt->get_result(); // get the mysqli result
			
			return $result;

		}catch(Exception $e){

		}
		

	}

	private function getTokenInfo($token){

		try{

			$now = time();
			$sql = "SELECT * FROM $this->table_name WHERE token=PASSWORD(?) AND expired_at>?";

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("ss", $token,$now);
	        $stmt->execute();
	        $result = $stmt->get_result(); // get the mysqli result
			if($user = $result->fetch_assoc()){
				return (object) $user;
	         
			}

		}catch(Exception $e){

		}

	}

	public function verifyToken($token){
		$tokenInfo = $this->getTokenInfo($token);
		if($tokenInfo){
			$this->deleteToken($token);

			
			$userModel = new UserModel();
			$userInfo = $userModel->getUserInfoById($tokenInfo->userId);

			return $userInfo;

		}else{

			
			$this->cleanupToken();
			return false;
		}

		

	}

	private function deleteToken($token){

		try{

			$sql = "DELETE FROM $this->table_name WHERE token=PASSWORD(?);";

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("s", $token );
		    $stmt->execute();
		    $result = $stmt->get_result(); // get the mysqli result
			

			return $result;

		}catch(Exception $e){

		}


	}

	private function cleanupToken(){

		$now = time();
		try{

			$sql = "DELETE FROM $this->table_name WHERE expired_at<?;";

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("s", $now );
		    $stmt->execute();
		    $result = $stmt->get_result(); // get the mysqli result
			
			return $result;

		}catch(Exception $e){

		}

	}

	


	


}
?>