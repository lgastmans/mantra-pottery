<?php
	require_once("db_mysqli.php");

	$sql = "SELECT * FROM `stock` WHERE height > 0 GROUP BY product_id ORDER BY product_id DESC";

	$res = $conn->Query($sql);

	if (!$res) {

		echo $conn->error;

	}
	else {

		while ($obj = $res->fetch_object()) {

			$sql = "
				UPDATE `product` 
				SET height = '".$obj->height."',
					width = '".$obj->width."',
					weight = '".$obj->weight."',
					volume = '".$obj->volume."'
				WHERE product_id = ".$obj->product_id;

			$update = $conn->Query($sql);

			if (!$update) {

				echo $conn->error." <br>";

			}
			else {

				echo "updated id ".$obj->product_id." <br>";

			}

		}

	}

  ?>