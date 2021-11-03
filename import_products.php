<?php
	require_once("include/db_mysqli.php");

//	$sql = "ALTER TABLE `author` AUTO_INCREMENT=1";
//	$qry = $conn->Query($sql);


	$filename = 'documents/glaze.csv';


	$save=true;


if ($file = fopen($filename, "r")) {


	$row_id=0;

	$text = '';


    while(!feof($file)) {

        $line = fgets($file);

       	$arr = explode(',',$line);

//print_r($arr);       	


		/*
			save 
		*/
		if ($save) {

			$sql = "
				INSERT INTO glaze
				(
					code,
					description
				)
				VALUES (
					'".mysqli_real_escape_string($conn, str_replace('"', '', $arr[0]))."',
					'".mysqli_real_escape_string($conn, str_replace('"', '', $arr[1]))."'
				)
			";

			$qry = $conn->Query($sql);

			if (!$qry) {
				echo "ERROR: ".$conn->error."<br>".$sql;
			}
			else {
				$row_id = $conn->insert_id;
				echo $row_id.". ".$arr[0]."<br>";
			}

		}

    }

    fclose($file);
}
else
	echo "could not open file $filename";
?>