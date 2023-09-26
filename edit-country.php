<?php 

$directory = 'assets/img/country_flags/';
$update_conditions = "";
foreach (glob($directory."*.jpg") as $key => $filename) {
    $file = realpath($filename);
    $file_name = str_replace("assets/img/country_flags/", "", $filename);
    $country_name = str_replace(".jpg", "", $file_name);
    $update_conditions .= "<br />
    UPDATE `countries` SET `photo_flag`='$file_name' WHERE name LIKE '%$country_name%',";
    
}
echo $update_conditions;