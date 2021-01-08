<?php
/**
 * Created by PhpStorm.
 * User: passion
 * Date: 2021/1/7
 * Time: 3:05 AM
 */
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES['file']['name']);
$uploadOk = 1;
$csvFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// init return data
$csv_data = [];

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($csvFileType != "csv") {
    echo "Sorry, only csv files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
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
        }
        echo json_encode($csv_data);
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}