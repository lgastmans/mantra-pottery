<?php

	require_once("include/db_mysqli.php");


	class db {

		public $db_login;
		public $db_password;
		public $db_database;

		public $backup_folder;
		public $purge;
		public $purge_pattern;
		public $filename;

		public $error_msg;


		function __construct()
		{

			$this->backup_folder = '';
			$this->purge = false;
			$this->purge_pattern = '';
			$this->filename = 'db_backup';

			$this->error_msg = '';
			
		} // construct


		public function backup()
		{

			$os = php_uname('s');


			/*
				check if the backup folder exists
			*/
			if (!file_exists($this->backup_folder)) {

				if (!mkdir($this->backup_folder)) {

					$this->error_msg = "Could not create folder '".$this->backup_folder."' - please create the folder manually and try again";

					return false;
				}

			}


			$temp_filename = "mysqlexport.sql";


			$this->filename .= "_".date('Y')."_".date('m')."_".date('j').".sql.gz";


			/*
				purge previous backup files 
			*/
			if ($this->purge) {

				if (!empty($this->purge_pattern)) {

					$arr = glob($this->backup_folder."/".$this->purge_pattern."*");

					if (count($arr) > 0) {

						foreach ($arr as $file) {
							unlink($file);
						}
					}

				}

			}



			/*
				check if the file was already created
			*/
			if (file_exists($this->backup_folder."/".$this->filename))
			{

				$this->error_msg = "Backup file $filename already exists";
				
				return false;
			}


			/*
				create the temporary database backup file
			*/
			if ($os == "Linux") {

				$strExportCommand = "/usr/bin/mysqldump -a -u ".$this->db_login." --password=".$this->db_password." ".$this->db_database." > \"".$this->backup_folder."/".$temp_filename."\"";

				exec($strExportCommand);
				
				$data = implode("", file($this->backup_folder."/".$temp_filename));

				$gzdata = gzencode($data);

			}
			else {

				$strExportCommand = "mysqldump -a -u ".$this->db_login." --password=".$this->db_password." ".$this->db_database." > \"".$this->backup_folder."/".$temp_filename."\"";

				exec($strExportCommand);
				
				$data = implode("", file($this->backup_folder."/".$temp_filename));

				$gzdata = gzencode($data);

			}


			/*
				write the zipped data to file
			*/
		    $fp = fopen($this->backup_folder."/".$this->filename, "w");

			if (!$fp)
			{
				$this->error_msg = 'error creating zip file';
				return false;
			}

		    fwrite($fp, $gzdata);
		    fclose($fp);


		    unlink($this->backup_folder."/".$temp_filename);


		    return true;

		} // function backup

	}  // class db

?>
