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

<h1> ADD NEW CONTRACT </h1> 

<form action="add_contract_m.php" method="post" enctype="multipart/form-data">
  <label>Contractor's name:</label><br>
  <input type="text" name="contractor"><br>
  <label>Expiry date:</label><br>
  <input type="text" name="expiry_date"><br>
  <input type="file" name="file" size="500000" />
  <br><br>
  <input type="submit" value="Add to database">
</form> 

<p style="color:red;">
<?php 
 if (isset($_SESSION['add_contract_msg']))
 {
	 echo $_SESSION['add_contract_msg'];
	 unset($_SESSION['add_contract_msg']);
 }
?>
</p>

</body>
</html>