<?php
class UserModel extends BaseModel{

	private $table_name="users";
	public function __construct(){
		 parent::__construct();
	}

	public function createUser($username,$password,$fullname,$role=0){
		//TODO: CREATE SQL QUERY TO CREATE USER

		
		try{

			$sql = "INSERT INTO $this->table_name SET username=?,password=PASSWORD(?),fullname=?,role=0,status=1";

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("sss",  $username,$password,$fullname);
		    $stmt->execute();
		    $result = $stmt->get_result(); // get the mysqli result
			

			$id = $stmt->insert_id;

			return $this->getUserInfoById($id);

		}catch(Exception $e){

		}


		return true;

	}

	public function authenticate($username,$password){

		try{

			$sql = "SELECT id,username,fullname,role FROM $this->table_name WHERE username=? AND password=PASSWORD(?) AND status = 1 LIMIT 1;";

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("ss", $username, $password);
	        $stmt->execute();
	        $result = $stmt->get_result(); // get the mysqli result
			if($user = $result->fetch_assoc()){
				return (object) $user;
	         
			}

		}catch(Exception $e){

		}

		return false;

	}

	public function verifyOldPassword($userid,$password){


		try{

			$sql = "SELECT id,username,fullname,role FROM $this->table_name WHERE id=? AND password=PASSWORD(?) AND status = 1 LIMIT 1;";

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("ss", $userid, $password);
	        $stmt->execute();
	        $result = $stmt->get_result(); // get the mysqli result
			if($user = $result->fetch_assoc()){
				return (object) $user;
	         
			}

		}catch(Exception $e){

		}



		return false;

	}

	public function getUserInfoById($id){
		try{

			$sql = "SELECT id,username,fullname,role,status FROM $this->table_name WHERE id=?";

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("s", $id);
	        $stmt->execute();
	        $result = $stmt->get_result(); // get the mysqli result
			if($user = $result->fetch_assoc()){
				return (object) $user;
	         
			}

		}catch(Exception $e){

		}

		return false;

	}




	public function changePassword($userId,$old_password,$new_password){

		$verifyOldPassword = $this->verifyOldPassword($userId,$old_password);
		if(!$verifyOldPassword){
			return false;
		}
		

		try{

			$sql = "UPDATE $this->table_name SET password=PASSWORD(?) WHERE id=?";

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("ss",  $new_password,$userId);
		    $stmt->execute();
		    $result = $stmt->get_result(); // get the mysqli result


			
			return true;

		}catch(Exception $e){

		}

	echo "GOES HERE";		

		

		return false;

		
	}






	public function fetchUsers($page=1,$limit=1000){

		$users = array();

		try{

			$sql = "SELECT id,username,fullname,role,status FROM $this->table_name ORDER BY id ASC LIMIT ?;";

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("s", $limit);
	        $stmt->execute();
	        $result = $stmt->get_result(); // get the mysqli result
			while($user = $result->fetch_assoc()){

				$users[] = (object) $user;

				

	         
			}

		}catch(Exception $e){
			echo $e->getMessage();

		}

		return $users;


	}

	public function changeUserStatus($userId,$status){

		//TODO: write query

		try{

			$sql = "UPDATE $this->table_name SET status=? WHERE id=?";

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("ss", $status, $userId);
		    $stmt->execute();
		    $result = $stmt->get_result(); // get the mysqli result
			
			return $result;

		}catch(Exception $e){

		}
		
	}

	public function enableUser($userId){
		return $this->changeUserStatus($userId,1);

	}
	public function disableUser($userId){
		return $this->changeUserStatus($userId,0);


	}


}
?>