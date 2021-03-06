<?php
include "../inc/init.php";

if(!$user->isAdmin()) {
    header("Location: $set->url/login.php");
    exit;
}

$page->title = "Edit Page";
$presets->setActive("adminpanel");
$page->keyword = "";

include "header.php";
include "menu-side.php";

 function renderForm($id,$menu,$menu_link,$error)
 {

 // if there are any errors, display them
 if ($error != '')
 {
 echo '<div class="container">
		 <div class="alert alert-danger" role="alert">
		 '.$error.'
		 </div>
		</div>';
 }
echo '

 <legend>Edit Page</legend>
 <form action="" method="post" class="form-horizontal" style="width: 100%;">
 
 <input type="hidden" name="id" value="'.$id.'"/>	
 
	<div class="form-group">
      <label class="col-sm-2 control-label" for="sitename">Menu Name</label>
      <div class="col-sm-9">
        <input type="text" class="form-control" name="menu" value="'.$menu.'"/>
      </div>
    </div>
	
	<div class="form-group">
      <label class="col-sm-2 control-label" for="sitename">Link Menu</label>
      <div class="col-sm-9">
        <input type="text" class="form-control" name="menu_link" value="'.$menu_link.'">
      </div>
    </div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-9">
	 	<input class="btn btn-primary" type="submit" name="submit" value="Submit"> <a href="navigation.php"><button type="button" class="btn btn-default">Cancle</button></a>
		</div>
	</div>
 </form>';
 }
 // connect to the database
 	$servername = "$set->db_host";
	$username = "$set->db_user";
	$password = "$set->db_pass";
	$dbname = "$set->db_name";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

 
 // check if the form has been submitted. If it has, process the form and save it to the database
 if (isset($_POST['submit']))
 { 
 // confirm that the 'id' value is a valid integer before getting the form data
 if (is_numeric($_POST['id']))
 {
 // get form data, making sure it is valid
 	$id = $_POST['id'];
	$menu= mysqli_real_escape_string($conn,$_POST['menu']);
	$menu_link = mysqli_real_escape_string($conn,$_POST['menu_link']);

 
 // check that firstname/lastname fields are both filled in
 if ($menu == '' || $menu_link == '' )
 {
 // generate error message
 $error = '<div class="container"><div class="alert">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong>Warning!</strong> Please fill in all required fields!
    </div></div>';
 
 //error, display form
 renderForm($id,$menu,$menu_link,$error);
 }
 else
 {
 // save the data to the database
 $insert = "UPDATE `".MLS_PREFIX."mainmenu` SET `menu`='$menu',`menu_link`='$menu_link' WHERE `id`='$id'";
 mysqli_query($conn, $insert)
 or die(mysqli_error()); 

 // once saved, redirect back to the view page
 ?>
 <script>window.location.replace("navigation.php");</script>
 <?php 

 }
 }
 else
 {
 // if the 'id' isn't valid, display an error
 echo '<div class="container">
		 <div class="alert alert-danger" role="alert">
		 Error the id in the URL isnt valid!
		 </div>
		</div>';
}
 }
 else
 // if the form hasn't been submitted, get the data from the db and display the form
 {
 
 // get the 'id' value from the URL (if it exists), making sure that it is valid (checing that it is numeric/larger than 0)
 if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0)
 {
 // query db
 $id = $_GET['id'];
 $result = mysqli_query($conn, "SELECT * FROM `".MLS_PREFIX."mainmenu` WHERE id=$id") 
		or die(mysqli_error()); 
 $row = mysqli_fetch_array($result);
 
 // check that the 'id' matches up with a row in the databse
 if($row)
 {
 
 // get data from db
$menu=$row['menu'];
$menu_link=$row['menu_link'];


 
 // show form
 renderForm($id,$menu,$menu_link, '');
 }
 else
 // if no match, display result
 {
 echo "No results!";
 }
 }
 else
 // if the 'id' in the URL isn't valid, or if there is no 'id' value, display an error
 {
 echo '<div class="container">
		 <div class="alert alert-danger" role="alert">
		 Error the id in the URL isnt valid, or there is no id value!
		 </div>
		</div>';
 }
 }
 
 include "admin-footer.php";
?>