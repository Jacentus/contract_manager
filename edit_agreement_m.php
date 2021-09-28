<?php
include_once("db.php");
session_start(); 

if (!isset($_SESSION['id'])){
	header("Location: index.php");
	die();
}

if (isset($_POST['agreement_id']) && isset($_POST['contractor_name']) && isset($_POST['input_date']) && isset($_POST['expiry_date']) 
&& isset($_POST['old_filename'])&& isset($_FILES['file']))
{
	$contractor_name = $_POST['contractor_name'];

	$conn = connectToDatabase();
	
/////////////////////////////////////////// CHECK IF CONTRACTOR ALREADY EXISTS  /////////////////////////////////////////////////////////
	
	$querry = "SELECT ID FROM contractor_cmt WHERE name=:contractor_name";
	
	$stm = oci_parse($conn, $querry);
	
	oci_bind_by_name($stm, ':contractor_name', $contractor_name);
	
	if (!oci_execute($stm))
	{
		echo "Błąd bazy danych";
		die();
	}
	
	$numrow = oci_fetch_all($stm, $arr);
	
	if ($numrow == 0)
	{
		$_SESSION['main_screen_msg']="No such contractor in the database! Add a contractor first.";
		oci_close($conn);
		header("Location: main_screen.php");
		die();
	}
	else //if contractor exists, retrieve his ID
	{
	$contractor_id = $arr['ID']['0'];	
	}
	
	oci_free_statement($stm);
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$input_date = $_POST['input_date'];
	$expiry_date = $_POST['expiry_date'];
	$agreement_id = $_POST['agreement_id'];
	$old_filename = $_POST['old_filename'];
	
///////////////////////////////////////////////// DELETE PREVIOUS FILE //////////////////////////////////////////////	
	
	$old_filepath = "agreements/".$old_filename;	
	
	if(!unlink($old_filepath))
	{
	$_SESSION['main_screen_msg']= "Unable to delete the contract! Check if the file exists.";
	oci_close($conn);
	header("Location: main_screen.php");
	die();
	}
	
	//////////////////////////////////////////// UPLOAD NEW FILE /////////////////////////////////////////////////
	
	$targetfolder = "agreements/";

	$targetfolder = $targetfolder . basename( $_FILES['file']['name']) ;

	if(move_uploaded_file($_FILES['file']['tmp_name'], $targetfolder))
	{
	echo "The file ". basename( $_FILES['file']['name']). " is uploaded";
	}
	else
	{
	echo "Problem with uploading file";
	}
	$new_filename = $_FILES['file']['name'];
	
	///////////////////////////////////////// UPDATE CONTRACT DETAILS  ///////////////////////////////////////////////////
	
	$q = "UPDATE agreement_cmt SET CONTRACTOR = :contractor_id, EXPIRY_DATE = :expiry_date, INPUT_DATE = :input_date, FILE_NAME = :new_filename WHERE id=:agreement_id";
	
	$stm = oci_parse($conn, $q);
	oci_bind_by_name($stm,':agreement_id',$agreement_id);
	oci_bind_by_name($stm,':contractor_id', $contractor_id);
	oci_bind_by_name($stm,':input_date',$input_date);
	oci_bind_by_name($stm,':expiry_date',$expiry_date);
	oci_bind_by_name($stm,':new_filename',$new_filename);

	if (!oci_execute($stm))
	{
		echo "Błąd bazy danych";
		die();
	}
	oci_free_statement($stm);
	oci_close($conn);
	$_SESSION['main_screen_msg'] = "Contract has been updated!";
	header("Location: main_screen.php");
}
else
{
	$_SESSION['main_screen_msg'] = "Sth went terribly wrong!";
	header("Location: main_screen.php");
}
