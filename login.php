<?php
require_once('includes_all.php');
$errors    = array();
$FullName  = "";
$Rusername = "";
$Rpassword = "";
$username  = "";
$password  = "";
$Rmobile   = "";

function validate_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


$userModel = new UserModel();


if (isset($_POST['action']) and $_POST['action'] == "Register") {
    
    
    $FullName  = validate_input($_POST['FullName']);
    $Rusername = validate_input($_POST['Rusername']);
    $Rpassword = validate_input($_POST['Rpassword']);
    $Rmobile =   validate_input($_POST['Rmobile']);
    $role      = 0; //New User
    
    if (empty($FullName)) {
        array_push($errors, "Name is required");
    }
    if (empty($Rusername)) {
        array_push($errors, "Username is required");
    }
    if (empty($Rpassword)) {
        array_push($errors, "Password is required");
    }
    
    if ($userInfo = $userModel->createUser($Rusername, $Rpassword, $FullName, 0)) {
        

        SessionManager::login($userInfo->username, $userInfo->id, $userInfo->role,$userInfo->fullname);
        header("Refresh:0; url=index.php");
        exit();
        
    }
    
} elseif (isset($_POST['action']) and $_POST['action'] == "Login") {
    
    $username = validate_input($_POST['username']);
    $password = validate_input($_POST['password']);
    
       
    if (empty($username)) {
        array_push($errors, "Username is required to login");
    }
    if (empty($password)) {
        array_push($errors, "Password is required to login");
    }
    
    if ($userInfo = $userModel->authenticate($username, $password)) {
        
        
        SessionManager::login($userInfo->username, $userInfo->id, $userInfo->role,$userInfo->fullname);
        header("Refresh:0; url=index.php");
        exit();
        
    } else {
        
        if (!empty($username) and !empty($password)) {

            array_push($errors, "The username or password that you've entered is incorrect.");
            header("Refresh:0; url=login.php");
            die();
        }
}

}
 

?>

<!DOCTYPE html>
<html lang="en"
<html>
   <title>MiniFacebook</title>
   <head>
      <link href="style.css" rel="stylesheet">
      <meta name="Description" content="Facebook Style Homepage Design with Login Form registration for using html and CSS" />
   </head>
   <body>
      <div class="minifb-header-base">
      </div>
      <div class="minifb-header">
         <div id="img1" class="minifb-header">
            <h1>MiniFaceBook
            </h1>
         </div>
         <form method="post" action="login.php">
            <div id="form1" class="minifb-header">Username
               <br>
               <input placeholder="Username" type="mail"    name="username" value="<?php echo $username; ?>" />
               <br>
               
            </div>
            <div id="form2" class="minifb-header">Password
               <br>
               <input placeholder="Password" type="password"   name="password" value="<?php echo $password; ?>" />
               <br>
              
            </div>
      </div>
      </div>
      <input type="submit" name ="action" class="submit1" value="Login" />
      </form>
      <div class="minifb-body">
         <div id="intro1" class="minifb-body">
            SecAD Project
            </br> Instructor - Dr.Phu Phung 
            </br> Design By - Kartik & Summer
            <?php include('errors.php'); ?>
         </div>
         <form  method="post" action="login.php">
         <div id="intro2" class="minifb-body">Create an account
         </div>
         <div id="form3" class="minifb-body">
            <input placeholder="Name" type="text" id="namebox" name="FullName" value="<?php echo $FullName; ?>" />
            <br>
            <input placeholder="Username" required  pattern="\w+" name="Rusername" type="text" id="mailbox" value="<?php echo $Rusername; ?>" />
            <br>
           
            <input placeholder="Mobile" type="teL" required  name ="Rmobile" id ="mailbox" pattern="[0-9]{10}" value="<?php echo $Rmobile; ?>"/>
            <br>

            <input placeholder="Password"  name="Rpassword" type="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" onchange="form.repassword.pattern = this.value;" id="mailbox" value="<?php echo $Rpassword; ?>" />

            <br>
            <input placeholder="Confirm Password" type="password"  id="mailbox" name="repassword" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" />
           <p>Note:For password at least one number, one lowercase and one uppercase letter
with at least six characters</p>
            <br>
            <input type="submit" name = "action" class="button2" value="Register" />
            <br>
            <hr>
         </div>
      </div>
   </body>
</html>
