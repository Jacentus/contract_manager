<?php
include_once("db.php");
session_start(); 

if (!isset($_SESSION['id'])){
	header("Location: index.php");
	die();
}

if (isset($_POST['contractor']) && isset($_POST['expiry_date']) && isset($_FILES['file']))
{
	$contractor = $_POST['contractor'];
	
/////////////////////////////////////////// CHECK IF CONTRACTOR ALREADY EXISTS  /////////////////////////////////////////////////////////
	
	$conn = connectToDatabase();
	
	$querry = "SELECT ID FROM contractor_cmt WHERE name=:contractor";
	
	$stm = oci_parse($conn, $querry);
	
	oci_bind_by_name($stm, ':contractor', $contractor);
	
	if (!oci_execute($stm))
	{
		echo "Błąd bazy danych";
		die();
	}
	
	$numrow = oci_fetch_all($stm, $arr);
	
	if ($numrow == 0)
	{
		$_SESSION['add_contractor_msg']="No such contractor in the database! Add contractor first and then try again!";
		oci_close($conn);
		header("Location: add_contractor.php"); //TU MÓGŁBYM PRZEKAZAĆ JAKOŚ ZMIENNĄ $contractor do pliku i wyświetlić już to, co wpisał użytkownik
		die();
	}	
	else 
	{
	$contractor = $arr['ID'][0];	
	}
	
	oci_free_statement($stm);
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$expiry_date = $_POST['expiry_date'];
	
	//////////////////////////////////////////////////////////// FILE HANDLING //////////////////////////////////////////////////////////////////////////////

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
	
	$filename = $_FILES['file']['name']; //aby uniknąć duplikatów nazw - hash nazwy o czas, zapis oryginalnej w innej tabeli w bazie danych, powiązać ze sobą tabele
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	///////////////////////////////////////////////////////// INSERT INTO DATABASE /////////////////////////////////////////////
	
	$q = "INSERT INTO agreement_cmt (CONTRACTOR, EXPIRY_DATE, CREATED_BY, FILE_NAME) VALUES(:contractor, :expiry_date, :id_user, :filename)";
		
	$stm = oci_parse($conn, $q);
	
	oci_bind_by_name($stm,':contractor',$contractor);
	oci_bind_by_name($stm,':expiry_date',$expiry_date);
	oci_bind_by_name($stm,':id_user',$_SESSION['id']);
	oci_bind_by_name($stm,':filename',$filename);
	
	if (!oci_execute($stm))
	{
		echo "Błąd bazy danych";
		die();
	}
	oci_free_statement($stm);
	oci_close($conn);
	
	$_SESSION['main_screen_msg'] = "NEW CONTRACT HAS BEEN ADDED TO THE DATABASE";
	
	header("Location: main_screen.php");
}
else
{
	header("Location: index.php");
}
