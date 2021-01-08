<?php
/**
 * Created by PhpStorm.
 * User: passion
 * Date: 2021/1/5
 * Time: 7:42 PM
 */
$tbl_data = file_get_contents("flag.json");
$tbl_array_data = json_decode($tbl_data, true);
$final_tbl_data = json_encode($tbl_array_data);

$uploaded = file_get_contents("upload.json");
$array_data = json_decode($uploaded, true);
$uploaded_list = json_encode($array_data);

if(isset($_POST['show_name']))
{
    $target_file = './uploads/' . $_POST['show_name'];
    $csv_data = [];
    // Open the file for reading
    if (($h = fopen("{$target_file}", "r")) !== FALSE)
    {
        // Each line in the file is converted into an individual array that we call $data
        // The items of the array are comma separated
        while (($data = fgetcsv($h, 1000, ";")) !== FALSE)
        {
            // Each individual array is being pushed into the nested array
            $csv_data[] = $data;
        }

        // Close the file
        fclose($h);
        echo json_encode($csv_data);
    }
}