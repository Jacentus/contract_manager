
<?php 
include_once("db.php");
session_start(); 

if (!isset($_SESSION['id'])){
	header("Location: index.php");
	die();
}

if (isset($_GET['id'])) 
{
	$id = $_GET['id']; //id umowy
}
else
{
	header("Location: index.php");
	die();
}

    $conn = connectToDatabase();
	
	$q = "SELECT AGREEMENT_CMT.CONTRACTOR,AGREEMENT_CMT.INPUT_DATE,AGREEMENT_CMT.EXPIRY_DATE,AGREEMENT_CMT.CREATED_BY,
	AGREEMENT_CMT.FILE_NAME,CONTRACTOR_CMT.ID,CONTRACTOR_CMT.NAME FROM AGREEMENT_CMT
	INNER JOIN CONTRACTOR_CMT ON AGREEMENT_CMT.CONTRACTOR = CONTRACTOR_CMT.ID 
	WHERE AGREEMENT_CMT.id = :id";
		
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

/////////////////////////////////////////////	CHECK IF USER IS ALLOWED TO EDIT  ///////////////////////////////////////
	
	if ($_SESSION['id'] != $arr['CREATED_BY'][0])
	{
		$_SESSION['main_screen_msg']= "You're not authorised to edit this data! Please contact agreement's supervisor!";
		header("Location: main_screen.php");
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

<form action="edit_agreement_m.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="agreement_id" value="<?php echo $id;?>">
  <input type="hidden" name="old_filename" value="<?php echo $arr['FILE_NAME'][0];?>">
  <label>Contractor:</label><br>
  <input type="text" name="contractor_name" value="<?php echo $arr['NAME'][0];?>"><br>
  <label>Input date:</label><br>
  <input type="text" name="input_date" value="<?php echo $arr['INPUT_DATE'][0];?>"><br>
  <label>Expiry date:</label><br>
  <input type="text" name ="expiry_date" value="<?php echo $arr['EXPIRY_DATE'][0];?>"><br>
  <input type="file" name="file" size="500000" />
  <br><br>
  <input type="submit" value="Modify">
</form> 

</body>
</html>

