<?php

include_once("db.php");

session_start();

if (isset($_POST['login']) && isset($_POST['password']))
{
	$login = $_POST['login'];
	$password = $_POST['password'];
	
	$connection = connectToDatabase();
	
	$q = "SELECT login, pass, ID FROM user_cmt
  WHERE login=:login AND
  pass=DBMS_CRYPTO.HASH(UTL_RAW.CAST_TO_RAW(:password),3)";
	
	
	$stm = oci_parse($connection, $q);
	oci_bind_by_name($stm,':login',$login);
	oci_bind_by_name($stm,':password',$password);
	oci_bind_by_name($stm, ':ID', $id);
	
	if (!oci_execute($stm))
	{
		echo "database error";
		die();
	}
	
	$numrow = oci_fetch_all($stm, $arr);
	oci_free_statement($stm);
	oci_close($connection);
	
	if ($numrow == 1)
	{
		$_SESSION['login'] = $login;
		$_SESSION['id'] = $arr['ID'][0];
		
		header("Location: main_screen.php");
		die();
	}
	else
	{
		$_SESSION['error'] = "Incorrect login or password";
	}
}
else
{
}

header("Location: index.php");
