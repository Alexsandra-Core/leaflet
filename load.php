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