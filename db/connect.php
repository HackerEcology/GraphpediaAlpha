<?php
	require '/var/secinc.php';
	$db = new mysqli('127.0.0.1','root',$mspwd,'graphpedia_wikireader');
	//echo $db->connect_errno;		// 0 if no error
	if($db->connect_errno){
		echo $db->connect_error,'<br>';	//Debug: specific
		die("sorry, we are having some problems");
	}
?>