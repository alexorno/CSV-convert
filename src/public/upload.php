<?php

require_once "../Controllers/FileValidate.php";
require_once "../Controllers/HomeController.php";
session_start();
header('location: /');

$validator = new FileValidate;
$controller = new Controllers\HomeController;
$file_tmp = $_FILES['fileToUpload']['tmp_name'];
$tmp_generated_name = substr($_FILES['fileToUpload']['tmp_name'], 4) . '.csv';
$addedCsv = file('../fileStorage/' . $tmp_generated_name);

$validator->checkCsvFile($_FILES);
// if check was succesful we can accept and move file to our folder
move_uploaded_file($file_tmp, "../fileStorage" . $tmp_generated_name);
$controller->addFile($addedCsv);