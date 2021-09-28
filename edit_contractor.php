<?php
include_once("db.php");
session_start(); 

if (!isset($_SESSION['id'])){
	header("Location: index.php");
	die();
}

if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['city']) && isset($_POST['registry_number']))
{
	$street = $_POST['street'];
	$city = $_POST['city'];
	$name = $_POST['name'];
	$id = $_POST['id'];
	$post_code = $_POST['post_code'];
	$registry_number = $_POST['registry_number'];
}

else
{
	header("Location: error.php");
}
?>	

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<title>Contract Manager Tool</title>
</head>
<body>

<h1><strong> EDIT CONTRACTOR's DATA </strong></h1> 

<form action="edit_contractor_m.php" method="post">
  <input type="hidden" name ="id" value="<?php echo $id;?>"><br>
  <label>Contractor's name:</label><br>
  <input type="text" name="name" value="<?php echo $name;?>"><br>
  <label>Seat (city):</label><br>
  <input type="text" name="city"value="<?php echo $city;?>"><br>
  <label>Street:</label><br>
  <input type="text" name="street"value="<?php echo $street;?>"><br>
  <label>Post code::</label><br>
  <input type="text" name="post_code"value="<?php echo $post_code;?>"><br>
  <label>Registry number:</label><br>
  <input type="text" name="registry_number"value="<?php echo $registry_number;?>"><br>
  <br>
  <input type="submit" value="Change contractor's details">
</form> 

</body>
</html>
