<?php

declare(strict_types = 1);

require_once "../Controllers/HomeController.php";
require_once "../View/View.php";

$controler = new Controllers\HomeController();
$view = new View\View();

$show = $controler->getData();

print_r($view->uploadForm());
print_r($view->uploadErrorMessage());
print_r($view->createTable($show));