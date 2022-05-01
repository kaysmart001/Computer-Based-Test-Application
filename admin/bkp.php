<?php
//    $dbhost = 'localhost';
//    $dbuser = 'root';
//    $dbpass = '';
   
//    $conn = mysql_connect($dbhost, $dbuser, $dbpass);
   
//    if(! $conn ) {
//       die('Could not connect: ' . mysql_error());
//    }
    
//    $table_name = "user_test";
//    $backup_file  = "e2066380.sql";
//   $sql = "LOAD DATA INFILE '$backup_file' INTO TABLE $table_name";
//     //  $sql = "SELECT * INTO OUTFILE '$backup_file' FROM $table_name";

   
//    mysql_select_db('examinar');
//    $retval = mysql_query( $sql, $conn );
   
//    if(! $retval ) {
//       die('Could not load data : ' . mysql_error());
//    }
//    echo "Loaded  data successfully\n";

// $backup_name = 'kkk';
//    // header('Content-Type: application/octet-stream');   header("Content-Transfer-Encoding: Binary"); 
//    // header("Content-disposition: attachment; filename=\"".$backup_name."\"");  echo $backup_file ; exit;
   
//    mysql_close($conn);


   /* creates a compressed zip file */
function create_zip($files = array(),$destination = '',$overwrite = false) {

    //if the zip file already exists and overwrite is false, return false
    //if(file_exists($destination) && !$overwrite) { return false; }
    //vars

    $valid_files = array();
    //if files were passed in...
    if(is_array($files)) {
          
        //cycle through each file
        foreach($files as $file) {
            //make sure the file exists
            if(file_exists($file)) {
                $valid_files[] = $file;
            }
        }
    }
    //if we have good files...
    if(count($valid_files)) {
        //create the archive
        $zip = new ZipArchive();
        if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
            return false;
        }
        //add the files
        foreach($valid_files as $file) {
            $zip->addFile($file,$file);
        }
        //debug
        //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
        
        //close the zip -- done!
        $zip->close();

        echo('successfully');
        
        //check to make sure the file exists
        return file_exists($destination);
    }
    else
    {
        return false;
    }
}

$files_to_zip = array(
    'confirm_session.php',
    'xx.php'
);
//if true, good; if false, zip creation failed
$result = create_zip($files_to_zip,'my-archive.zip'. true);


if (file_exists('xxx.php')){ echo "yeah"; }else{ echo 'ohps';}
?>