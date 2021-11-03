<?php
   
   require_once("db_mysqli.php");


$data = array();

if(isset($_FILES['file']['name'])){

   /* Getting file name */
   $filename = $_FILES['file']['name'];

   /* Location */
   $location = "../images/".$filename;
   $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
   $imageFileType = strtolower($imageFileType);

   /* Valid extensions */
   $valid_extensions = array("jpg","jpeg","png");

   $response = 0;
   /* Check file extension */
   if(in_array(strtolower($imageFileType), $valid_extensions)) {

      /* Upload file */
      if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){

         $data['error'] = false;
         $data['msg'] = 'image uploaded successfully';

         /*
            add to table
         */
         $sql = "
            INSERT INTO stock_images
            (
               stock_id,
               filename
            )
            VALUES (
               '".mysqli_real_escape_string($conn, $_POST['stock_id'])."',
               '".mysqli_real_escape_string($conn, $filename)."'
            )
         ";

         $qry = $conn->Query($sql);

         if (!$qry) {
            $data['error'] = true;
            $data['msg'] = $conn->error; //.":".$sql;
         }

      }
      else {
         $data['error'] = true;
         $data['msg'] = 'file upload failed';
      }
   }
   else {
      $data['error'] = true;
      $data['msg'] = 'invalid file extension';
   }
}
else {

   $data['error'] = true;
   $data['msg'] = 'File name not set';

}

echo json_encode($data);