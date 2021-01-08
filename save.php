<?php
/**
 * Created by PhpStorm.
 * User: passion
 * Date: 2021/1/4
 * Time: 11:02 PM
 */

    $data_for_csv = $_POST["data"];
    $filename = $_POST["filename"];

    $json_data= json_encode($data_for_csv);
    $data_for_write = json_decode($json_data, true);

    // set new csv file name.
    $new_csv_file = $filename . ".csv";

    try {
        //create a new csv file and store it.
        $new_csv = fopen("csv/" . $new_csv_file, "w");
        $delimiter = ";";

        foreach ($data_for_write as $row){
            // write the row to the csv file.
            fputcsv($new_csv, $row, $delimiter);
        }

        // close the file.
        fclose($new_csv);

        // save log in flag
        $log_result = input_log($new_csv_file, $filename);

        // echo "Saved all markers info in " . $new_csv_file . ".";
        echo $log_result;
    }
    catch (Exception $e)
    {
        echo "Caught exception: ",  $e->getMessage(), "\n";
    }

    function input_log($downloadUrl, $filename)
    {
        if (file_exists("flag.json")){
            $current_data = file_get_contents("flag.json");
            $array_data = json_decode($current_data, true);
            $extra = array(
                "downloadUrl" => $downloadUrl,
                "filename" => $filename
            );
            $array_data[] = $extra;
            $final_data = json_encode($array_data);
            if (file_put_contents("flag.json", $final_data))
            {
                return $final_data;
            }

        }
        else{
            $error = "JSON file not exists";
        }
    }