<?php

require_once('includes_all.php');

$message ="";

 if(!SessionManager::isUserLoggedIn())
  header("Refresh:0; url=login.php");

if (isset($_POST['action']) and $_POST['action'] == "Change") {

  $oldpassword=$_POST['oldpassword'];
  $newpassword=$_POST['newpassword'];
  $nocsrftoken=$_POST['nocsrftoken'];

  if(SessionManager::validateCRSFToken($nocsrftoken)){

    $userModel = new UserModel();
    if($userModel->changePassword(SessionManager::getUserId(),$oldpassword,$newpassword)){
      $message ="<span style='color:blue'> Success </span>";

    }else{
      //Errors
      $message ="<span style='color:red'> Wrong Old password </span>";
    }

  }else{
    //Errors
    $message ="<span style='color:red'> CRSF Attack </span>";
  }



}

$rand = SessionManager::generateCRSFToken();
  
  





?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Password Change page - SecAD</title>
  <style>
  body{
    text-align:center;
    background-color:#F6F4F2;
  }
  .container{
 background-color:#FFFFFF;
  padding:10px;
  text-align: center;
  color:black;
  width: 37%;
    margin: auto;

  font-family:verdana;
  -webkit-box-shadow: 0 3px 8px rgba(0, 0, 0, .25);
  }
   input{
    background-color: #f2f3f5;
    outline: none;
    border-radius: 5px;
    height: 19px;
    border: none;
    padding: 5px;
    margin-bottom: 10px;
}
.button{
 background:#5b7bc0;
 color:white;
 border-radius:5px;
 cursor:pointer;
 padding:12px;
}
h1{
  color:#3d5b99;
}
input[type=submit]:hover {
  background-color: #45a049;
}
  </style>
</head>
<body>
<div class="container">
        <h1>Change Password</h1>
           <h4> Username: <?php echo htmlentities(SessionManager::getUserName()); ?></h4>
           <h5><?php echo date("jS \of F Y ") . "<br>";?></h5>
           <h5><?php echo $message?></h5>
          <form action="changepassword.php"  method="POST" class="form login">       
          
 <br> 
<input type="hidden" name="nocsrftoken" value="<?php echo $rand;?>"/>     
                Oldpassword: <input type="password" class="text_field" name="oldpassword" /> <br>        
                Newpassword: <input type="password" class="text_field" name="newpassword" /> <br>
                <button name="action" value="Change" class="button" type="submit">
                  Change
                </button>
          </form>
          </div>

</body>
</html>




