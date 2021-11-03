<?php
	 // $db_db = 'mantra-pottery';
	 // $db_server = 'localhost';
	 // $db_login = 'worktree';
	 // $db_password = 'wtdb@Online';
	
	$db_db = 'mantra-pottery';
	$db_server = 'localhost';
	$db_login = 'root';
	$db_password = 'LGastmans@1969';

	$conn = new mysqli($db_server, $db_login, $db_password, $db_db);

	if ($conn->connect_errno) {
	    printf("Connect failed: %s\n", $conn->connect_error);
	    exit();
	}
?>