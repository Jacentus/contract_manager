<?php 
session_start();  
include_once("db.php");

if (!isset($_SESSION['id'])){
	header("Location: index.php");
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

<h1> Jacek's contract manager </h1> 

<br>

<a href="add_contract.php"><strong>Add a new agreement</strong></a>
<br>
<a href="add_contractor.php"><strong>Add a new contractor</strong></a>

<br><br>
<a href="logout.php">Log out [<?php echo $_SESSION['login'];?>]</a>  
<br><br>
 
<form method="post"> 
<label> Sort table by:</label>
<select name="sort">
  <option value="ORDER BY AGREEMENT_CMT.ID ASC">Contract's ID asc.</option>
  <option value="ORDER BY AGREEMENT_CMT.ID DESC">Contract's ID desc.</option>
  <option value="ORDER BY CONTRACTOR_NAME ASC">Contractor asc.</option>
  <option value="ORDER BY CONTRACTOR_NAME DESC">Contractor desc.</option>
  <option value="ORDER BY USER_CMT.NAME ASC">Supervisor asc.</option>
  <option value="ORDER BY USER_CMT.NAME DESC">Supervisor desc.</option> 
  <input type="submit" value="Sort data">
</select>
</form> 
<br>
 
<table>
    <tr>
      <th>ID</th>
	  <th>Contractor's name </th>
	  <th>Concluded on</th>
	  <th>Expires on</th>
	  <th>Supervisor</th>
	  <th>VIEW AGREEMENT</th>
	  <th>DELETE</th>
	  <th>EDIT<th/>
	</tr>  

<?php

///////////////////////////////////////////////// SORT TABLE ////////////////////////////////////////////////////////

if (isset($_POST['sort']))
{
$sort = (string)$_POST['sort'];
}
else $sort = null;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$conn = connectToDatabase();
	
// TO DZIALALO $q = "SELECT ID, CONTRACTOR, INPUT_DATE, EXPIRY_DATE, CREATED_BY, FILE_NAME FROM AGREEMENT_CMT";

// DZIALA NA DWIE TABELE $q = "SELECT AGREEMENT_CMT.ID, AGREEMENT_CMT.CONTRACTOR, AGREEMENT_CMT.INPUT_DATE, AGREEMENT_CMT.EXPIRY_DATE, AGREEMENT_CMT.CREATED_BY, AGREEMENT_CMT.FILE_NAME, CONTRACTOR_CMT.NAME FROM AGREEMENT_CMT INNER JOIN CONTRACTOR_CMT ON AGREEMENT_CMT.CONTRACTOR=CONTRACTOR_CMT.ID";

$q = "SELECT AGREEMENT_CMT.ID, AGREEMENT_CMT.CONTRACTOR, AGREEMENT_CMT.INPUT_DATE, AGREEMENT_CMT.EXPIRY_DATE, AGREEMENT_CMT.CREATED_BY, AGREEMENT_CMT.FILE_NAME, 
CONTRACTOR_CMT.NAME AS CONTRACTOR_NAME, USER_CMT.SURNAME, USER_CMT.NAME 
FROM AGREEMENT_CMT INNER JOIN CONTRACTOR_CMT ON AGREEMENT_CMT.CONTRACTOR=CONTRACTOR_CMT.ID 
INNER JOIN USER_CMT ON AGREEMENT_CMT.CREATED_BY=USER_CMT.ID ".$sort;

$stm = oci_parse($conn, $q);
		
	if (!oci_execute($stm))
	{
		echo "Błąd bazy danych przy sortowaniu";
		die();
	}
	
	$numrow = oci_fetch_all($stm, $arr);
	oci_free_statement($stm);
	oci_close($conn);
	
	for ($i=0;$i<$numrow;$i++)
	{
		echo "<tr>
		<td>{$arr['ID'][$i]}</td>
		<td>
		<a href=\"contractors_details.php?id={$arr['CONTRACTOR'][$i]}\">{$arr['CONTRACTOR_NAME'][$i]}</a>
		</td>
		<td>{$arr['INPUT_DATE'][$i]}</td>
		<td>{$arr['EXPIRY_DATE'][$i]}</td>
		<td>
		<a href=\"user_details.php?id={$arr['CREATED_BY'][$i]}\">{$arr['NAME'][$i]} {$arr['SURNAME'][$i]}</a>
		</td>
		<td>
		<a target = '_blank' href=\"read_pdf.php?filename={$arr['FILE_NAME'][$i]}\">
		<img width=\"32\" src=\"gfx/view.png\">
		</td>
		<td>
		<a href=\"delete_agreement.php?id={$arr['ID'][$i]}&filename={$arr['FILE_NAME'][$i]}\">
		<img width=\"32\" src=\"gfx/delete.png\">
		</a>
		</td>
		<td>
		<a href=\"edit_agreement.php?id={$arr['ID'][$i]}\">
		<img width=\"32\" src=\"gfx/edit.png\">
		</a>
		</td>
		</tr>";
	}
?>
</table>

<p style="color:red;">
<?php 
 if (isset($_SESSION['main_screen_msg']))
 {
	 echo $_SESSION['main_screen_msg'];
	 unset($_SESSION['main_screen_msg']);
 }
?>
</p>

</body>
</html>




