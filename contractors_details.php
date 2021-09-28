<?php 
include_once("db.php");
session_start(); 

if (!isset($_SESSION['id'])){
	header("Location: index.php");
	die();
}

if (isset($_GET['id'])) 
{
	$id = $_GET['id'];
}
else
{
	header("Location: index.php");
	die();
}

$conn = connectToDatabase();
	
	$q = "SELECT NAME, CITY, STREET, POST_CODE, REGISTRY_NUMBER FROM contractor_cmt WHERE id=:id";
		
	$stm = oci_parse($conn, $q);
	oci_bind_by_name($stm,':id',$id);
		
	if (!oci_execute($stm))
	{
		echo "Błąd bazy danych";
		die();
	}
	
	$numrow = oci_fetch_all($stm, $arr);
	oci_free_statement($stm);
	oci_close($conn);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<title>Contract Manager Tool</title>
</head>
<body>

<h1> CONTRACTOR'S DETAILS </h1> 

<?php

echo "Name: ".$arr['NAME'][0]."<br>".
" Registry seat: ".$arr['CITY'][0].", ".$arr['STREET'][0].", ".$arr['POST_CODE'][0]."<br>".
"Registry number: ".$arr['REGISTRY_NUMBER'][0];

?>
<br><br><br>
<form action="edit_contractor.php" method="post">
  <input type="hidden" name="id" value="<?php echo $id;?>">
  <input type="hidden" name="name" value="<?php echo $arr['NAME'][0];?>">
  <input type="hidden" name="city" value="<?php echo $arr['CITY'][0];?>">
  <input type="hidden" name ="street" value="<?php echo $arr['STREET'][0];?>">
  <input type="hidden" name="post_code" value="<?php echo $arr['POST_CODE'][0];?>">
  <input type="hidden" name="registry_number" value="<?php echo $arr['REGISTRY_NUMBER'][0];?>">
  <input type="submit" value="Edit contractor's details">
</form>

<br><br>
<a href="main_screen.php"><strong>Back to main page</strong></a>
<br><br>
<a href="logout.php">Log out [<?php echo $_SESSION['login'];?>]</a>  
</body>
</html>