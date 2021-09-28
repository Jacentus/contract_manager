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
	
	$conn = connectToDatabase(); 
	
	/////////////////////////////////////////// CHECK IF THERE IS MORE THAN ONE CONTRACTOR WITH THAT NAME, I.E. IT ALREADY EXISTS  /////////////////////////////////////////////////////////
	
	$querry = "SELECT ID FROM contractor_cmt WHERE name=:name";
	
	$stm = oci_parse($conn, $querry);
	
	oci_bind_by_name($stm, ':name', $name);
	
	if (!oci_execute($stm))
	{
		echo "Błąd bazy danych";
		die();
	}
	
	$numrow = oci_fetch_all($stm, $arr);
	
	if ($numrow >= 1)
	{
		$_SESSION['main_screen_msg']="Such contractor already exists!";
		oci_close($conn);
		header("Location: main_screen.php");
		die();
	}
	
	oci_free_statement($stm);
	
	//////////////////////////////////////////// UPDATE CONTRACTOR INFO /////////////////////////////////////////////////////////////////////////
	
	$q = "UPDATE contractor_cmt SET NAME = :name, CITY = :city, STREET = :street, POST_CODE = :post_code, REGISTRY_NUMBER = :registry_number WHERE id=:id";
	
	$stm = oci_parse($conn, $q);
	oci_bind_by_name($stm,':id',$id);
	oci_bind_by_name($stm,':name', $name);
	oci_bind_by_name($stm,':city',$city);
	oci_bind_by_name($stm,':street',$street);
	oci_bind_by_name($stm,':post_code',$post_code);
	oci_bind_by_name($stm,':registry_number',$registry_number);

	if (!oci_execute($stm))
	{
		echo "Błąd bazy danych";
		die();
	}
	oci_free_statement($stm);
	oci_close($conn);
	header("Location: main_screen.php");
}
else
{
	header("Location: error.php");
}