<?php
class PostModel extends BaseModel{

	private $table_name="posts";

	function __construct(){
		 parent::__construct();
	}

	public function createPost($post){

		if(!SessionManager::isUserLoggedIn()){
			return false;
		}

		$userId = SessionManager::getUserId();

		
		try{

			$sql = "INSERT INTO  $this->table_name SET userId=?,message=? ";
			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("is", $userId, $post);
		    $stmt->execute();
		    $id = $stmt->insert_id;

			
		    return $this->getPostInfo($id);
		}catch(Exception $e){

			// echo $e->getMessage();

		}
		

		//return ID of the Post
		return false;

	}

	public function getPostInfo($id,$withComment=true){
		$postInfo = false;

		try{

			$sql = "SELECT * FROM $this->table_name WHERE id=? LIMIT 1;";

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("s", $id);
	        $stmt->execute();
	        $result = $stmt->get_result(); // get the mysqli result
	        
			if($post = $result->fetch_assoc()){

				$userModel = new UserModel();

				$postInfo= (object) $post;

				$postInfo->owner= $userModel->getUserInfoById($postInfo->userId);
				$postInfo->comments=[];

				if($withComment){
					$commentModel = new CommentModel();
					$postInfo->comments = $commentModel->loadComments($postInfo->id);
				}	
	         
			}

		}catch(Exception $e){

		}


		
		return $postInfo;

	}

	public function getAllPosts($limits=10){

		$posts = array();

		try{

			$sql = "SELECT id FROM $this->table_name ORDER BY ID DESC LIMIT ?";

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("s", $limits);
	        $stmt->execute();
	        $result = $stmt->get_result(); // get the mysqli result

	        $userModel = new UserModel();
	        
			while($post = $result->fetch_assoc()){

				$posts[] = $this->getPostInfo($post['id']);

				

	         
			}

		}catch(Exception $e){

		}


		
		return $posts;
	}

	private function checkIfOwner($postId){

		$postInfo = $this->getPostInfo($postId);

		if(!$postInfo){
			// Post not found
			return false;
		}

		if($postInfo->userId != SessionManager::getUserId()){
			//Not the owner
			return false;
		}

		return true;

	}

	public function editPost($postId, $newPost){

		if(!$this->checkIfOwner($postId)){
			return false;
		}

		//TODO: SQL Query to Edit the Post

		try{

			$sql = "UPDATE $this->table_name  SET message =?  WHERE id=? ";
			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("si", $newPost, $postId);
		    $stmt->execute();

			
		    return true;
		}catch(Exception $e){

			// echo $e->getMessage();

		}

		return true;

	}

	


	public function deletePost($id){
		if(!$this->checkIfOwner($id)){
			return false;
		}



		try{

			$sql = "DELETE FROM  $this->table_name WHERE id=? ";
			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("i", $id);
		    $stmt->execute();

			
		    return true;
		}catch(Exception $e){

			// echo $e->getMessage();

		}

		return true;



	}

	


}
?>