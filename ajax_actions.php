<?php
require_once('includes_all.php');


$response = array();

if(!SessionManager::isUserLoggedIn()){
	$response['status'] = false;
	$response['data'] = "Unauthentication";

	echo json_encode($response);
	exit();
}

if(!isset($_POST['action'])){
	$response['status'] = false;
	$response['data'] = "Invalid Method";

	echo json_encode($response);
	exit();

}

//Admin actions
$action = $_POST['action'];

if($_POST['action_area']=="admin"){

	if(!SessionManager::isAdmin()){
		$response['status'] = false;
		$response['data'] = "You are not admin";

		echo json_encode($response);
		exit();
	}


	if($action=="user_status"){
		$userId = $_POST['userid'];
		$userModel = new UserModel();

		$userInfo = $userModel->getUserInfoById($userId);
		if($userInfo && $userInfo->role==0){
			if($userInfo->status==0){
				$status  = $userModel->enableUser($userId);
				$response['data'] = "<span style='color:red'>Disable User</span>";
				//Use for replacing the button
			}else{
				$status  = $userModel->disableUser($userId);
				$response['data'] = "<span style='color:blue'>Enable User</span>";
				//Use for replacing the button
			}

			$response['status'] = true;
			

		}else{
			$response['status'] = false;
			$response['data'] = "You are not authorized";

		}


	}




}





//User Actions

if($_POST['action_area']=="user"){


// %1$s  - for postIndex
// %2$s  - for postText
// %3$s  - for username/post_title


	
	if($action=="new_post") {

				$postText = $_POST['post'];
				$post_model = new PostModel();

				$postInfo =  $post_model->createPost($postText);

				if($postInfo){
					$response['status'] = true;
						$html = 
		'<div  class="f-card" id="div_%1$s">
	    <div class="header">
	    <div class="options">
	    <i class="fa fa-chevron-down"></i>
	    </div>
	    <img class="co-logo" src="http://placehold.it/40x40" />
	    <div class="co-name">
	    <a href="#">%3$s</a>
	    <input  id="editable_%1$s"  class="editable" type="submit" value="Edit"/>
	    <input  id="remove_%1$s" class="remove" type="submit" value="Delete"/>
	    </div>
	    <div class="time">
	    <a href="#">2hrs</a> ·
	     <i class="fa fa-globe"></i>
	     </div>
	    </div>
	 <div  class="content">
	    <p id="edit_%1$s" class="Ediv">%2$s</p>
	  </div>
	    <div class="social">
	    <div class="social-buttons">
	      <span><i class="fa fa-comment"></i>Comment</span>
	         <span><i class="icon"></i>0</span>
	      </div>
	  </div>
	  <div class="comment_list" id="comment_list_%1$s"></div>
	  <div class="Wcomment">
	                				<div class="comment_author_profile_picture">
	        							<img src="http://placehold.it/40x40" />
	        						</div>		 		   

					<div class="Wcomment_text">
	    	            				<textarea id="txtcomment_%1$s" class="txtcomment" placeholder="Write a comment..."></textarea>				
	            				
	                 </div> 
	                 </div>
	     
	
	    </div> ' ;
	     		$response['data'] = sprintf($html,htmlentities($postInfo->id),htmlentities($postInfo->message),htmlentities($postInfo->owner->fullname));


				}else{

					$response['status'] = false;
					$response['data']="Error while posting";

				}


					

			} else if($action=="new_comment"){

				$commentModel = new CommentModel();
				$comment_text = $_POST['comment_text'];
				$postId = $_POST['post_id'];

				$commentInfo =  $commentModel->createComment($postId,$comment_text);


            
			$comment_html =
			'<div class="comment" id="comment_%s">
<div class="comment_author_profile_picture">
                          <img class="co-logo" src="http://placehold.it/40x40" /> 
                    </div>
<div class="comment_details">
<div class="comment_author" >
<span>%s</span> 
</div>
              <div class="comment_text" >
                              <p >%s</p>
                              
                            </div>
                      </div>
                   </div>';
	
  $response['data'] = sprintf($comment_html,htmlentities($commentInfo->id),htmlentities($commentInfo->owner->fullname),htmlentities($commentInfo->comment));
	

		}else if($action=="delete_post"){

					$post_id = $_POST['post_id'];
					$post_model = new PostModel();

					$status =  $post_model->deletePost($post_id);
					if($status){
						$response['status'] = true;
						$response['data']="OK";

					}else{
						$response['status'] = false;
						$response['data']="Error while deleting post";

		}
		





		

	} else if($action=="delete_comment"){

		$response['status'] = true;
						$response['data']="OK";



	}

	else if($action=="edit_post"){

					$post_id = $_POST['post_id'];
					$post = $_POST['post'];
					$post_model = new PostModel();

					$status =  $post_model->editPost($post_id,$post);
					if($status){
						$response['status'] = true;
						$response['data']="OK";

					}else{
						$response['status'] = false;
						$response['data']="Error while deleting post";

		}
	}


}





echo json_encode($response);
exit();

?>
