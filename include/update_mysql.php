<?php
  require_once("db_mysqli.php");
//  require_once("session.php");



// https://stackoverflow.com/questions/6432178/how-can-i-check-if-a-mysql-table-exists-with-php
// The cleanest way to achieve this in PHP is to simply use DESCRIBE statement.
// if(mysql_query("DESCRIBE `table`")) {
//     // Exists
// }



	function insert_table($table_name, $sql) {

		global $conn;

		$res = $conn->Query("DESCRIBE `".$table_name."`");

		if (!$res) {

			foreach($sql as $row) {

				$res = $conn->Query($row);

				if (!$res) {
					return "function insert_table tried to execute: ".$row."<br>"; //res->error;
				}

			}
		    return "TABLE $table_name CREATED <br>";

		}
		else {

			//return "TABLE $table_name EXISTS ALREADY<br>";
			return "CHECKED $table_name <br>";

		}

	}



	function insert_column($table_name, $sql) {

		global $conn;

		$column_name = strtok($sql, "\`");

		$res = $conn->Query("SELECT ".$column_name." FROM ".$table_name);

		if (!$res) {

		    $res = $conn->Query("ALTER TABLE ".$table_name." ADD ".$sql);

			if (!$res) {
				return $conn->error." <br>";
			}

		    return $column_name.' HAS BEEN ADDED TO '.$table_name."<br>";

		} else {

		    //return $sql." : ".$table_name." -> ".$column_name.' exists already<br>';
			return '';
		}

	}


	
	function execute_update($stamp, $sql) {

		global $conn;

		$res = $conn->Query("SELECT * FROM update_log WHERE type_id = ".$stamp);

		if (!$res) {

			return $conn->error." <br>";

		}
		else {

			if ($res->num_rows > 0) {

				return "$stamp already updated<br>";

			}
			else {

				$res = $conn->Query($sql);

				if (!$res) {

					return $conn->error." : ".$sql." <br>";

				}
				else {

					$res = $conn->Query("
						INSERT INTO update_log 
							(type_id, updated_on, user_id) 
							VALUES('".$stamp."', '".date('Y-m-d H:i:s')."', '1') 
					");

					return $stamp." UPDATED<br>";

				}
			}

		}
	}



	$sql=array();



	$sql[0] = "
		CREATE TABLE `update_log` (
		  `id` int(11) NOT NULL,
		  `type_id` int(11) NOT NULL,
		  `updated_on` datetime NOT NULL,
		  `filename` varchar(128) NOT NULL,
		  `user_id` int(11) NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

	$sql[1] = "ALTER TABLE `update_log` ADD PRIMARY KEY (`id`);";

	$sql[2] = "ALTER TABLE `update_log` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

	echo insert_table('update_log', $sql);

	echo insert_column('update_log', "`id` int(11) NOT NULL");
	echo insert_column('update_log', "`type_id` int(11) NOT NULL");
	echo insert_column('update_log', "`updated_on` datetime NOT NULL");
	echo insert_column('update_log', "`filename` varchar(128) NOT NULL");
	echo insert_column('update_log', "`user_id` int(11) NOT NULL");



	$sql[0] = "
		CREATE TABLE `design` (
		  `design_id` int(11) NOT NULL,
		  `code` varchar(64) NOT NULL,
		  `description` varchar(256) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;	
	";

	$sql[1]= "
		ALTER TABLE `design`
		  ADD PRIMARY KEY (`design_id`),
		  ADD UNIQUE KEY `code` (`code`);
	";

	echo insert_table('design', $sql);

	echo insert_column('design', "`design_id` int(11) NOT NULL");
	echo insert_column('design', "`code` varchar(64) NOT NULL");
	echo insert_column('design', "`description` varchar(256) NOT NULL");



	$sql[0] = "
		CREATE TABLE `glaze` (
		  `glaze_id` int(11) NOT NULL,
		  `code` varchar(64) NOT NULL,
		  `description` varchar(256) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;
	";

	$sql[1]= "
		ALTER TABLE `glaze`
		  ADD PRIMARY KEY (`glaze_id`),
		  ADD UNIQUE KEY `code` (`code`);
  	";

	echo insert_table('glaze', $sql);

	echo insert_column('glaze', "`glaze_id` int(11) NOT NULL");
	echo insert_column('glaze', "`code` varchar(64) NOT NULL");
	echo insert_column('glaze', "`description` varchar(256) NOT NULL");




	$sql[0] = "
		CREATE TABLE `product` (
		  `product_id` int(11) NOT NULL,
		  `code` varchar(64) NOT NULL,
		  `description` varchar(256) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;
	";

	$sql[1]= "
		ALTER TABLE `product`
		  ADD PRIMARY KEY (`product_id`),
		  ADD UNIQUE KEY `code` (`code`);
  	";

	echo insert_table('product', $sql);

	echo insert_column('product', "`product_id` int(11) NOT NULL");
	echo insert_column('product', "`code` varchar(64) NOT NULL");
	echo insert_column('product', "`description` varchar(256) NOT NULL");




	$sql[0] = "
		CREATE TABLE `settings` (
		  `id` int(11) NOT NULL,
		  `minimum_quantity` smallint(6) NOT NULL DEFAULT 1
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;
	";

	$sql[1]= "
		ALTER TABLE `settings`
		  ADD PRIMARY KEY (`id`);
  	";

  	$sql[2] = "
		INSERT INTO `settings` (`id`, `minimum_quantity`) VALUES (1, 4);
  	";

	echo insert_table('settings', $sql);

	echo insert_column('settings', "`id` int(11) NOT NULL");
	echo insert_column('settings', "`minimum_quantity` smallint(6) NOT NULL DEFAULT 1");



	$sql[0] = "
		CREATE TABLE `stock` (
		  `stock_id` int(11) NOT NULL,
		  `product_id` int(11) DEFAULT NULL,
		  `glaze_id` int(11) DEFAULT NULL,
		  `design_id` int(11) DEFAULT NULL,
		  `height` decimal(10,3) NOT NULL,
		  `width` decimal(10,3) NOT NULL,
		  `weight` decimal(10,3) NOT NULL,
		  `volume` decimal(10,3) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;
	";

	$sql[1]= "
		ALTER TABLE `stock`
		  ADD PRIMARY KEY (`stock_id`),
		  ADD UNIQUE KEY `stock_idx` (`product_id`,`glaze_id`,`design_id`),
		  ADD KEY `design_id` (`design_id`),
		  ADD KEY `glaze_id` (`glaze_id`);
  	";

	$sql[2]= "
		ALTER TABLE `stock`
		  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`design_id`) REFERENCES `design` (`design_id`),
		  ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
		  ADD CONSTRAINT `stock_ibfk_3` FOREIGN KEY (`glaze_id`) REFERENCES `glaze` (`glaze_id`);
	";

	echo insert_table('stock', $sql);

	echo insert_column('stock', "`stock_id` int(11) NOT NULL");
	echo insert_column('stock', "`product_id` int(11) DEFAULT NULL");
	echo insert_column('stock', "`glaze_id` int(11) DEFAULT NULL");
	echo insert_column('stock', "`design_id` int(11) DEFAULT NULL");
	// echo insert_column('stock', "`height` decimal(10,3) NOT NULL");
	// echo insert_column('stock', "`width` decimal(10,3) NOT NULL");
	// echo insert_column('stock', "`weight` decimal(10,3) NOT NULL");
	// echo insert_column('stock', "`volume` decimal(10,3) NOT NULL");




	$sql[0] = "
		CREATE TABLE `stock_images` (
		  `id` int(11) NOT NULL,
		  `stock_id` int(11) NOT NULL,
		  `filename` varchar(256) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;
	";

	$sql[1]= "
		ALTER TABLE `stock_images`
		  ADD PRIMARY KEY (`id`),
		  ADD KEY `product_id` (`stock_id`);
  	";

	echo insert_table('stock_images', $sql);

	echo insert_column('stock_images', "`id` int(11) NOT NULL");
	echo insert_column('stock_images', "`stock_id` int(11) NOT NULL");
	echo insert_column('stock_images', "`filename` varchar(256) NOT NULL");




	$sql[0] = "
		CREATE TABLE `stock_registry` (
		  `stock_registry_id` int(11) NOT NULL,
		  `stock_id` int(11) NOT NULL,
		  `quantity` int(11) NOT NULL,
		  `date` datetime DEFAULT NULL,
		  `comment` varchar(512) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;
	";

	$sql[1]= "
		ALTER TABLE `stock_registry`
		  ADD PRIMARY KEY (`stock_registry_id`),
		  ADD KEY `stock_id` (`stock_id`);
  	";

	$sql[2]= "
		ALTER TABLE `stock_registry`
		  ADD CONSTRAINT `stock_registry_ibfk_1` FOREIGN KEY (`stock_id`) REFERENCES `stock` (`stock_id`);
  	";

	echo insert_table('stock_registry', $sql);

	echo insert_column('stock_registry', "`stock_registry_id` int(11) NOT NULL");
	echo insert_column('stock_registry', "`stock_id` int(11) NOT NULL");
	echo insert_column('stock_registry', "`quantity` int(11) NOT NULL");
	echo insert_column('stock_registry', "`date` datetime DEFAULT NULL");
	echo insert_column('stock_registry', "`comment` varchar(512) NOT NULL");




	$sql[0] = "
		CREATE TABLE `reference_receive` (
		  `reference` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	";

	$sql[1] = "
		ALTER TABLE `reference_receive` ADD UNIQUE KEY `reference` (`reference`);
	";

	echo insert_table('reference_receive', $sql);

	echo insert_column('reference_receive', "`reference` int(11) NOT NULL");




	$sql[0] = "
		CREATE TABLE `company` (
		  `company_id` int(11) NOT NULL,
		  `legal_name` varchar(256) NOT NULL,
		  `trade_name` varchar(256) NOT NULL,
		  `branch` varchar(256) NOT NULL,
		  `address` varchar(512) NOT NULL,
		  `phone` varchar(64) NOT NULL,
		  `gstin` varchar(64) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	";

	$sql[1] = "
		ALTER TABLE `company`
		  ADD PRIMARY KEY (`company_id`);
	";

	$sql[2] = "
		ALTER TABLE `company`
		  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
	";

	$sql[3] = "
		INSERT INTO `company` (`company_id`, `legal_name`, `trade_name`, `branch`, `address`, `phone`, `gstin`) VALUES
		(1, 'Auroville Foundation', 'Team Trust', 'Mantra Pottery', 'Kottakarai, Auroville 605101', '+91 413 262 2567', '33AAATA0037B7ZL');
	";

	echo insert_table('company', $sql);

	echo insert_column('company', "`company_id` int(11) NOT NULL");
	echo insert_column('company', "`legal_name` varchar(256) NOT NULL");
	echo insert_column('company', "`trade_name` varchar(256) NOT NULL");
	echo insert_column('company', "`branch` varchar(256) NOT NULL");
	echo insert_column('company', "`address` varchar(512) NOT NULL");
	echo insert_column('company', "`phone` varchar(64) NOT NULL");
	echo insert_column('company', "`gstin` varchar(64) NOT NULL");




	$sql[0] = "
		CREATE TABLE `source` (
		  `source_id` int(11) NOT NULL,
		  `type_id` tinyint(4) NOT NULL,
		  `name` varchar(256) NOT NULL,
		  `address` varchar(512) NOT NULL,
		  `phone` varchar(128) NOT NULL,
		  `gstin` varchar(256) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	";

	$sql[1] = "
		ALTER TABLE `source`
		  ADD PRIMARY KEY (`source_id`);
	";

	$sql[2] = "
		ALTER TABLE `source`
		  MODIFY `source_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
	";

	echo insert_table('source', $sql);

	echo insert_column('source', "`source_id` int(11) NOT NULL");
	echo insert_column('source', "`type_id` tinyint(4) NOT NULL");
	echo insert_column('source', "`name` varchar(256) NOT NULL");
	echo insert_column('source', "`address` varchar(512) NOT NULL");
	echo insert_column('source', "`phone` varchar(128) NOT NULL");
	echo insert_column('source', "`gstin` varchar(256) NOT NULL");




	$sql[0] = "
		CREATE TABLE `stock_movement` (
		  `stock_movement_id` int(11) NOT NULL,
		  `source_id` int(11) NOT NULL,
		  `reference` varchar(32) NOT NULL,
		  `movement_type` smallint(6) NOT NULL,
		  `date` date NOT NULL,
		  `status` tinyint(4) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	";

	$sql[1] = "
		ALTER TABLE `stock_movement`
		  ADD PRIMARY KEY (`stock_movement_id`);
	";

	$sql[2] = "
		ALTER TABLE `stock_movement`
		  MODIFY `stock_movement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
	";

	echo insert_table('stock_movement', $sql);

	echo insert_column('stock_movement', "`stock_movement_id` int(11) NOT NULL");
	echo insert_column('stock_movement', "`source_id` int(11) NOT NULL");
	echo insert_column('stock_movement', "`reference` varchar(32) NOT NULL");
	echo insert_column('stock_movement', "`movement_type` smallint(6) NOT NULL");
	echo insert_column('stock_movement', "`date` date NOT NULL");
	echo insert_column('stock_movement', "`status` tinyint(4) NOT NULL");




	$sql[0] = "
		CREATE TABLE `stock_movement_items` (
		  `stock_movement_item_id` int(11) NOT NULL,
		  `stock_movement_id` int(11) NOT NULL,
		  `stock_id` int(11) NOT NULL,
		  `quantity` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	";

	$sql[1] = "
		ALTER TABLE `stock_movement_items`
		  ADD PRIMARY KEY (`stock_movement_item_id`);
	";

	$sql[2] = "
		ALTER TABLE `stock_movement_items`
		  MODIFY `stock_movement_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
	";

	echo insert_table('stock_movement_items', $sql);

	echo insert_column('stock_movement_items', "`stock_movement_item_id` int(11) NOT NULL");
	echo insert_column('stock_movement_items', "`stock_movement_id` int(11) NOT NULL");
	echo insert_column('stock_movement_items', "`stock_id` int(11) NOT NULL");
	echo insert_column('stock_movement_items', "`quantity` int(11) NOT NULL");






	/*

		updates

	*/
	echo execute_update(202102281, "ALTER TABLE `product` ADD `height` decimal(10,3) NOT NULL;");
	echo execute_update(202102282, "ALTER TABLE `product` ADD `width` decimal(10,3) NOT NULL;");
	echo execute_update(202102283, "ALTER TABLE `product` ADD `weight` decimal(10,3) NOT NULL;");
	echo execute_update(202102284, "ALTER TABLE `product` ADD `volume` decimal(10,3) NOT NULL;");
		  
	echo execute_update(20210310, "ALTER TABLE `stock` ADD `current_stock` INT NOT NULL DEFAULT '0' AFTER `volume`;");

	echo execute_update(20210512, "ALTER TABLE `stock_registry` ADD `entry_type` tinyint(4) NOT NULL;");



	echo execute_update(20210311, "ALTER TABLE `stock` DROP `height`, DROP `width`, DROP `weight`, DROP `volume`;");



	echo "done.";

?>