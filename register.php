<?php

if (empty($_POST['field'])) echo 0;
else {
	include($_SERVER['REAL_DOCUMENT_ROOT']."/config.php");

	$_POST['field']['email_addr'] = strtolower($_POST['field']['email_addr']);
	$_POST['field']['password'] = hash('sha256', $_POST['field']['email_addr'].$dbname.$_POST['field']['password']);

	foreach($_POST['field'] as $field => $value) {
		//echo ".$field." : ".$value."</br>";
		if (empty($value)) { echo 0; return; }
		$value = secure_input($value);

		if (!isset($user_fields)) $user_fields = $field;
		else $user_fields = $user_fields.",".$field;
		if (!isset($user_values)) $user_values = $value;
		else $user_values = $user_values.",".$value;
	}

	// check to make sure username hasn't been taken during registration process
	$result = mysqli_query($link, "SELECT `email_addr` FROM `".$table_prefix."users` WHERE `email_addr`=".secure_input($_POST['field']['email_addr'])."");
	if ($result && mysqli_num_rows($result) > 0) { echo 0; return; }

	$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	for ($p = 0; $p < 50; $p++) $reqcode .= $characters[mt_rand(0, strlen($characters))];
	
	$subject = "Email Verification - MyJobVid.com";
	$message = "<img src=\"http://myjobvid.com/images/logo1.png\"/><br />
		<p style=\"font-size:11pt;\">Please <a href=\"http://myjobvid.com/login.php?emailvercode=".$reqcode."\">Click Here</a> to confirm your email address.<br /><br />
		If the link above does not work, you can paste the following address into your browser:<br /><br /><a href=\"http://myjobvid.com/login.php?emailvercode=".$reqcode."\">http://myjobvid.com/login.php?emailvercode=".$reqcode."</a><br /><br />
		You will be asked to login to your account to confirm your email address before the account becomes activated.<br /><br />
		Thank you for using MyJobVid.com!<br /><br />Kind regards,<br />The MyJobVid.com Team.";
	$headers = "MIME-Version: 1.0;\r\n" . "Content-Type:text/html; charset=iso-8859-1;\r\n" . "From: MyJobVid <admin@myjobvid.com>\r\n";
	mail($_POST['field']['email_addr'], $subject, $message, $headers);

	mysqli_query($link, "INSERT INTO `".$table_prefix."users` (".$user_fields.",`email_verify_code`) VALUES(".$user_values.",'".$reqcode."')");
	$user_id = mysqli_insert_id($link);
	mysqli_query($link, "INSERT INTO `".$table_prefix."profiles` (`user_id`) VALUES(".$user_id.")");

    if ($result) mysqli_free_result($result);
    mysqli_close($link);
	
	echo 1;
}

?>
