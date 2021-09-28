<?php 
  
if (isset($_GET['filename']))
{
	$filename = "agreements/".$_GET['filename'];
	
	header("Content-type: application/pdf"); 
	header("Content-Length: " . filesize($filename)); 
	readfile($filename);

}
else
{
	$_SESSION['main_screen_msg'] = "Unable to open the file";
	header("Location: main_screen.php");
}  

?>  