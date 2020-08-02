<?php
class CommentModel extends BaseModel{

	private $table_name="comments";

	function __construct(){
		 parent::__construct();
	}


	private function loadComment($id){

		$commentInfo = false;

		try{

			$sql = "SELECT * FROM $this->table_name WHERE id=? LIMIT 1;";

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("s", $id);
	        $stmt->execute();
	        $result = $stmt->get_result(); // get the mysqli result
	        
			if($comment = $result->fetch_assoc()){

				$userModel = new UserModel();

				$commentInfo= (object) $comment;
				
				$commentInfo->owner= $userModel->getUserInfoById($commentInfo->userId);

	         
			}

		}catch(Exception $e){

		}


		
		return $commentInfo;



	}

	public function loadComments($postId,$limits=100){

		$comments = array();

		try{

			$sql = "SELECT id FROM $this->table_name WHERE postId=? ORDER BY ID ASC LIMIT ?";

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("is",$postId, $limits);
	        $stmt->execute();
	        $result = $stmt->get_result(); // get the mysqli result

	        
			while($comment = $result->fetch_assoc()){

				$comments[] = $this->loadComment($comment['id']);

				

	         
			}

		}catch(Exception $e){

		}


		
		return $comments;



	}

	public function createComment($postId,$comment){


		if(!SessionManager::isUserLoggedIn()){
			return false;
		}

		$userId = SessionManager::getUserId();

		

		
		try{

			$sql = "INSERT INTO  $this->table_name SET userId=?,postId=?,comment=? ";
			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("iis", $userId, $postId,$comment);
		    $stmt->execute();
		    $id = $stmt->insert_id;

			
		    return $this->loadComment($id);
		}catch(Exception $e){

			// echo $e->getMessage();

		}
		

		//return ID of the Post
		return false;

	}

}
?>