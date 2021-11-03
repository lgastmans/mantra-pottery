<?php
	require_once("session.php");

	$ret = array();

	if (isset($_POST['selected_nav'])) {

		$_SESSION['active_nav'] = $_POST['selected_nav'];

		$ret['msg'] = 'Ok';
		$ret['session_var'] = 'active_nav';
		$ret['session_val'] = $_POST['selected_nav'];

		if ($_POST['selected_nav'] == 'nav_receive')
			$_SESSION['movement_type'] = 1;
		else
			$_SESSION['movement_type'] = 2;

	}
	else {
		if (isset($_POST['session_var'])) {

			if ($_POST['session_var']=='inventory_filter') {

				$_SESSION['inventory_filter'] = $_POST['session_val'];

			}

			$ret['msg'] = 'Ok';
			$ret['session_var'] = $_POST['session_var'];
			$ret['session_val'] = $_POST['session_val'];

		}
	}

	echo json_encode($ret);
?>