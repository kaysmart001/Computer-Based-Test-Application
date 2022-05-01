<?php

if (isset($_POST["fsubmit"])){
    echo "<pre>";
    var_dump($_POST);
    var_dump($_FILES);
    $tmp_dir = "tmp/" . microtime(true);
    if (!file_exists($tmp_dir)){
        mkdir($tmp_dir);
    }
    if (is_uploaded_file($_FILES["file"]["tmp_name"])){
        move_uploaded_file($_FILES["file"]["tmp_name"], $tmp_dir . "/a.zip");
        exec("unzip -z -j $tmp_dir $tmp_dir" . "/a.zip");
        exec("ls $tmp_dir", $out);
        echo "Files in the archive:\n";
        foreach ($out as $file){
            $file = trim($file);
            echo "File: $file,", filesize($tmp_dir . "/" . $file)."b\n";
        }
        exec("rm -rf $tmp_dir");
    }
} else {
?>
<form action="" method="POST" enctype="multipart/form-data">
<input type="file" name="file" />
<input type="submit" name="fsubmit" value="upload">
</form>
<?php 
} ?>


<!-- UNZIPPING -->

// assuming file.zip is in the same directory as the executing script.
$file = 'file.zip';

// get the absolute path to $file
$path = pathinfo(realpath($file), PATHINFO_DIRNAME);

$zip = new ZipArchive;
$res = $zip->open($file);
if ($res === TRUE) {
  // extract it to the path we determined above
  $zip->extractTo($path);
  $zip->close();
  echo "WOOT! $file extracted to $path";
} else {
  echo "Doh! I couldn't open $file";
}

