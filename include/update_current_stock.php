<?php
	require_once("db_mysqli.php");

	$sql = "SELECT stock_id, product_id FROM `stock`";

	$res = $conn->Query($sql);

	if (!$res) {

		echo $conn->error;

	}
	else {

		while ($obj = $res->fetch_object()) {

			$sql = "
                SELECT sr.quantity
                FROM stock_registry sr
				WHERE (sr.stock_id = ".$obj->stock_id.")
                ORDER BY sr.`date` DESC
                LIMIT 1
            ";
            $registry = $conn->Query($sql);

            if (!$registry) {
				echo $conn->error." <br>".$sql."<br>";
            }
            else {

            	$row = $registry->fetch_object();

				$sql = "
					UPDATE `stock`
					SET current_stock = '".$row->quantity."'
					WHERE stock_id = ".$obj->stock_id;

				$update = $conn->Query($sql);

				if (!$update) {

					echo $conn->error." <br>";

				}
				else {

					echo "updated id ".$obj->stock_id." <br>";

				}
			}
		}

	}

  ?>