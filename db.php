<?php


function connectToDatabase()
{
	
	$dbstr ="(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)
(HOST=dbserver.mif.pg.gda.pl)(PORT = 1521))
(CONNECT_DATA = (SERVER=DEDICATED)
(SERVICE_NAME = ORACLEMIF)
))"; 

$charenc = 'AL32UTF8';

$connection = oci_connect('user','pass',$dbstr, $charenc);

if (!$connection) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

 $q = "ALTER SESSION SET NLS_NUMERIC_CHARACTERS='.,'";
 $stm = oci_parse($connection, $q);
 oci_execute($stm);
 oci_free_statement($stm);

return $connection;
}
