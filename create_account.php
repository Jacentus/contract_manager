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

<h1> Create new account </h1> 

<form action="create_account_m.php" method="post">
  <label>E-mail (your login. Please note you have to enter e-mail in @jacek.pl domain):</label><br>
  <input type="text" name="login"><br>
  <label>Password:</label><br>
  <input type="password" name="password"><br>
  <label>Confirm password:</label><br>
  <input type="password" name="confirm_password"><br>
  <label>Name:</label><br>
  <input type="text" name ="name"><br>
  <label>Surname:</label><br>
  <input type="text" name ="surname"><br>
  <label>Position:</label><br>
  <input type="text" name ="position"><br>
  <label>Employed since:</label><br>
  <input type="text" name ="employment_date"><br><br>
  <input type="submit" value="Create new account">
</form> 

<p style="color:red;">
<?php 
 if (isset($_SESSION['error_create_account']))
 {
	 echo $_SESSION['error_create_account'];
	 unset($_SESSION['error_create_account']);
 }
?>
</p>

</body>
</html>