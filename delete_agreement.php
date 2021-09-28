<?php
include_once("db.php");
session_start(); 

if (!isset($_SESSION['id'])){
	header("Location: index.php");
	die();
}
if (isset($_GET['id']) && ($_GET['filename']))
{
	$id = $_GET['id'];	
	$filepath = "agreements/".$_GET['filename'];
	
	$conn = connectToDatabase();
	
/////////////////////////////// CHECK IF THE USER IS ALLOWED TO DELETE GIVEN CONTRACT ////////////////////////////////
	
	$q = "SELECT CREATED_BY FROM AGREEMENT_CMT WHERE ID=:id";
	$stm = oci_parse($conn, $q);
	oci_bind_by_name($stm,':id',$id);
	if (!oci_execute($stm))
	{
		echo "Błąd bazy danych";
		die();
	}
	$numrow = oci_fetch_all($stm, $arr);
	oci_free_statement($stm);
	
	if ($_SESSION['id'] != $arr['CREATED_BY'][0])
	{
		$_SESSION['main_screen_msg']= "You're not authorised to delete this contract! Please contact agreement's supervisor!";
		
		oci_close($conn);
		header("Location: main_screen.php");
		die();
	}
	
/////////////////////////////////////////  DELETE FILE FROM SERVER  ////////////////////////////////////////////////////
	
	if(!unlink($filepath))
	{
	$_SESSION['main_screen_msg']= "Unable to delete the contract! Check if the file exists.";
	header("Location: main_screen.php");
	die();
	}

///////////////////////////////////////////  DELETE FROM DATABASE   ////////////////////////////////////////////////////	
	
	$q = "DELETE agreement_cmt WHERE ID=:id";	
	
	$stm = oci_parse($conn, $q);
	oci_bind_by_name($stm,':id',$id);

	if (!oci_execute($stm))
	{
		echo "Błąd bazy danych";
		die();
	}
	oci_free_statement($stm);

	oci_close($conn);
	
	$_SESSION['main_screen_msg']= "The agreement has been deleted!";
	
	header("Location: main_screen.php");
}
else
{
	$_SESSION['main_screen_msg']= "Sth went wrong!";
	header("Location: main_screen.php");
}
