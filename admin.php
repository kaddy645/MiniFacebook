<?php
require_once('includes_all.php');
require_once('includes/auto_redirect.php');


if(!SessionManager::isAdmin()){
	echo "Access Denied. You are not admin";
	exit();

}

$userModel = new UserModel();

$users = $userModel->fetchUsers(1,1000);

?>

<script type="text/javascript" charset="utf8" src="js/jquery-3.5.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="DataTables/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="DataTables/jquery.dataTables.js"></script>
 
<script>

$(document).ready( function () {
    $('#table_id').DataTable({
    	"paging":   true,
        "ordering": true,
        "info":     false
    });
} );


function toggleUserStatus(aObject,userid){

	$.post( "ajax_actions.php", { action_area: "admin", action: "user_status",userid:userid },function( data ) {
  		$(aObject).html(data.data);
    
  	},"json");
  


}

</script>

<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Id</th>
            <th>User Name</th>
            <th>Full Name</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    	<?php
    		foreach ($users as $user) {
    		?>
	    		<tr>
		            <td><?php echo htmlentities($user->id); ?></td>
		            <td><?php echo htmlentities($user->username); ?></td>
		            <td><?php echo htmlentities($user->fullname); ?></td>
		            <td><?php if($user->role==0) echo "User" ; else  echo "Admin"; ?></td>
		            <td><a onclick="javascript:toggleUserStatus(this,<?php echo htmlentities($user->id);?>);" type="button">
		            <?php if ($user->status==0) { echo "<span style='color:blue'>Enable User</span>";} else {echo "<span style='color:red'>Disable User</span>";} ?> </a>
		            </td>

	        	</tr>
    			
    	<?php	
    	}

    	?>
        
        
    </tbody>
</table>