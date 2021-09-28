<?php
include_once("db.php");
session_start(); 

if (!isset($_SESSION['id']))
{
	header("Location: index.php");
	die();
}

if (isset($_POST['name']) && isset($_POST['city']) && isset($_POST['registry_number'])) //TU WIECEJ PARAMETROW JESLI BEDZIE ROZBUDOWANY FORMULARZ
{
	$name = $_POST['name'];
	$city = $_POST['city'];
	$street = $_POST['street'];
	$post_code = $_POST['post_code'];
	$registry_number = $_POST['registry_number'];
	
	$conn = connectToDatabase();
	
	/////////////////////////////////////////// CHECK IF CONTRACTOR ALREADY EXISTS  /////////////////////////////////////////////////////////
	
	$querry = "SELECT ID FROM contractor_cmt WHERE name=:name";
	
	$stm = oci_parse($conn, $querry);
	
	oci_bind_by_name($stm, ':name', $name);
	
	if (!oci_execute($stm))
	{
		echo "Błąd bazy danych";
		die();
	}
	
	$numrow = oci_fetch_all($stm, $arr);
	
	if ($numrow != 0)
	{
		$_SESSION['main_screen_msg']="Such contractor already exists!";
		oci_close($conn);
		header("Location: main_screen.php");
		die();
	}	
	
	oci_free_statement($stm);
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$q = "INSERT INTO contractor_cmt (NAME, CITY, STREET, POST_CODE, REGISTRY_NUMBER) VALUES(:name, :city, :street, :post_code, :registry_number)";
		
	$stm = oci_parse($conn, $q);
	
	oci_bind_by_name($stm,':name',$name);
	oci_bind_by_name($stm,':city',$city);
	oci_bind_by_name($stm,':street',$street);
	oci_bind_by_name($stm, ':post_code', $post_code);
	oci_bind_by_name($stm,':registry_number',$registry_number);
	
	if (!oci_execute($stm))
	{
		echo "Błąd bazy danych";
		die();
	}
	
	oci_free_statement($stm);
	
	$_SESSION['main_screen_msg'] = "NEW CONTRACTOR HAS BEEN ADDED TO THE DATABASE";
	
	/*	TODO
	//////////////////////////////////////////////////// RETURN CONTRACTOR'S ID	/////////////////////////////////////////////////////////////////////////////////

	$conn = connectToDatabase();
	
	$q = "SELECT id FROM CONTRACTOR_CMT WHERE NAME = :name AND registry_number = :registry_number";
	
	$stm = oci_parse($conn, $q);
	
	if (!oci_execute($stm))
	{
		echo "Błąd bazy danych";
		die();
	}
	
	$numrow = oci_fetch_all($stm, $arr);
	oci_free_statement($stm);
	oci_close($conn);
	*/
	
	header("Location: main_screen.php");
}
else
{	
	$_SESSION[main_screen_msg] = "Sth went wrong!";
	header("Location: main_screen.php");
}



