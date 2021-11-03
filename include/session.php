<?php

	session_start();

	if (!isset($_SESSION['active_nav']) || (empty($_SESSION['active_nav'])))
		$_SESSION['active_nav'] = "nav-inventory";
	
	if (!isset($_SESSION['inventory_filter']) || (empty($_SESSION['inventory_filter'])))
		$_SESSION['inventory_filter'] = '__ALL_';

	if (!isset($_SESSION['movement_type']) || (empty($_SESSION['movement_type'])))
		$_SESSION['movement_type'] = 1;
	
	if (!isset($_SESSION['movement_details']) || (empty($_SESSION['movement_details'])))
		$_SESSION['movement_details'] = array();

	if (!isset($_SESSION['movement_items']) || (empty($_SESSION['movement_items'])))
		$_SESSION['movement_items'] = array();

?>