<?php 
session_start(); 

if (!isset($_SESSION['id'])){
	header("Location: index.php");
	die();
}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<title>Contract Manager Tool</title>
</head>
<body>

<h1> ADD NEW CONTRACTOR </h1> 

<form action="add_contractor_m.php" method="post">
  <label>Contractor's name:</label><br>
  <input type="text" name="name"><br>
  <label>Seat (city):</label><br>
  <input type="text" name="city"><br>
  <label>Street:</label><br>
  <input type="text" name="street"><br>
  <label>Post code::</label><br>
  <input type="text" name="post_code"><br>
  <label>Registry number:</label><br>
  <input type="text" name="registry_number"><br>
  <br>
  <input type="submit" value="Add to database">
</form> 

<p style="color:red;">

<?php 
 if (isset($_SESSION['add_contractor_msg']))
 {
	 echo $_SESSION['add_contractor_msg'];
	 unset($_SESSION['add_contractor_msg']);
 }
?>
</p>

</body>
</html>
