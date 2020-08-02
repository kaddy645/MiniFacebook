<?php
require_once('includes_all.php');
header('Access-Control-Allow-Origin: *');  


$response = array();




if(!isset($_REQUEST['action'])){
	$response['status'] = false;
	$response['data'] = "Invalid Method";

	echo json_encode($response);
	exit();

}
$action = $_REQUEST['action'];
if($action=="request_token"){
	if(!SessionManager::isUserLoggedIn()){
		$response['status'] = false;
		$response['data'] = "Unauthentication";

		echo json_encode($response);
		exit();
	}


	$chatModel = new ChatAuthModel();

	if($token = $chatModel->generateToken(SessionManager::getUserId())){

		$response['status'] = true;
		$response['data'] = $token;
	}else{
		$response['status'] = false;
		$response['data'] = "Unable to issue the token";
	}

}else if($action=="verify_token"){
	// print_r($_REQUEST);
	$chatModel = new ChatAuthModel();
	$token = $_REQUEST['token'];

	if($userInfo = $chatModel->verifyToken($token)){

		$response['status'] = true;
		$response['data'] = $userInfo;
	}else{
		$response['status'] = false;
		$response['data'] = "Token invalid";
	}

}



echo json_encode($response);
exit();


?>