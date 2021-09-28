<?php 
include_once("db.php");
session_start(); 

if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['position']) && 
isset($_POST['employment_date']) && isset($_POST['name']) && isset($_POST['surname']))
{
	$login = $_POST['login'];
	
	if (strpos($login, '@jacek.pl') === false)
	{
		$_SESSION['error_create_account'] = "Only employees can create an account! Use your e-mail in @jacek.pl domain.";
		header("Location: create_account.php");
		die();
	}
	
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];
		
	if ($password!=$confirm_password)
	{
		$_SESSION['error_create_account'] = "Passwords provided do not match! Try again.";
		header("Location: create_account.php");
	}
	
	$position = $_POST['position'];
	$employment_date = $_POST['employment_date'];
	$name = $_POST['name'];
	$surname = $_POST['surname'];
	
	/////////////////////////////////// CHECK IF ACCOUNT ALREADY EXISTS  /////////////////////////////////////////
	
	$connection = connectToDatabase();
	
	$q = "SELECT LOGIN FROM USER_CMT WHERE LOGIN=:login";
		
	$stm = oci_parse($connection, $q);
	oci_bind_by_name($stm,':login',$login);
		
	if (!oci_execute($stm))
	{
		echo "Błąd bazy danych";
		die();
	}
	$numrow = oci_fetch_all($stm, $arr);
	oci_free_statement($stm);
	
	if ($numrow == 1) //such account already exists
	{
		$_SESSION['error_create_account'] = "Such user already exists!";
		oci_close($connection);
		header("Location: create_account.php");
		die();
	}
	
	////////////////////////////      NEW ACCOUNT WILL BE CREATED  ////////////////////////////////////////////////
	
	//$connection = connectToDatabase();
	
	$querry = "INSERT INTO USER_CMT (LOGIN, PASS, NAME, SURNAME, POSITION, EMPLOYMENT_DATE) VALUES (:login, DBMS_CRYPTO.HASH(UTL_RAW.CAST_TO_RAW(:password),3), :name, :surname, :position, :employment_date)";

	$statement = oci_parse($connection, $querry);
	
	oci_bind_by_name($statement,':name',$name);
	oci_bind_by_name($statement,':surname',$surname);
	oci_bind_by_name($statement,':login',$login);
	oci_bind_by_name($statement,':password',$password);
	oci_bind_by_name($statement,':position',$position);
	oci_bind_by_name($statement,':employment_date',$employment_date);

	if (!oci_execute($statement))
	{
		echo "Błąd bazy danych";
		die();
	}
	oci_free_statement($statement);
	oci_close($connection);
	$_SESSION['error']="New user has been added to the database!";
	header("Location: index.php");
}
else
{
	$_SESSION['error_create_account'] = "Not all required data were provided. Try again.";
	header ("Location: create_account.php");
}

?>


