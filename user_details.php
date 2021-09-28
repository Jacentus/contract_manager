<?php 
session_start();
include_once("db.php");

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
	
	$q = "SELECT LOGIN, NAME, SURNAME, POSITION, EMPLOYMENT_DATE FROM user_cmt WHERE id=:id";
		
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

<h1> USER DETAILS </h1> 

<?php

echo "Name: ".$arr['NAME'][0].
" Surname: ".$arr['SURNAME'][0]."<br>".
" E-mail: ".$arr['LOGIN'][0]."<br>".
" Position: ".$arr['POSITION'][0]."<br>".
" Employed since: ".$arr['EMPLOYMENT_DATE'][0];

?>
<br><br><br>
<a href="main_screen.php"><strong>Back to main page</strong></a>
<br><br>
<a href="logout.php">Log out [<?php echo $_SESSION['login'];?>]</a>  
</body>
</html>