<?php 
session_start(); 
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<title>Contract Manager Tool</title>
</head>
<body>

<h1> Welcome in Jacek's Contract Manager Tool! </h1> 

<p>You don't have an account yet? <a href="create_account.php">CREATE NEW ACCOUNT</a></p>

<form action="verify_login.php" method="post">
  <label>Login:</label><br>
  <input type="text" name="login"><br>
  <label>Password:</label><br>
  <input type="password" name="password"><br><br>
  <input type="submit" value="Enter">
</form> 

<p style="color:red;">
<?php 
 if (isset($_SESSION['error']))
 {
	 echo $_SESSION['error'];
	 unset($_SESSION['error']);
 }
?>
</p>


</body>
</html>