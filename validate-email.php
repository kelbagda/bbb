<?php

if (!isset($_POST['email_addr'])) echo 0;
else {
	include($_SERVER['REAL_DOCUMENT_ROOT']."/config.php");

	$email_addr = strtolower($_POST['email_addr']);

	$result = mysqli_query($link, "SELECT `email_addr` FROM `".$table_prefix."users` WHERE `email_addr`=".secure_input($email_addr)."");

	if ($result && mysqli_num_rows($result) == 0) echo 1;
	else echo 0;
	
	if ($result) mysqli_free_result($result);
    mysqli_close($link);
}

?>