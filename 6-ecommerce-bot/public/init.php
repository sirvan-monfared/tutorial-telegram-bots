<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
//ini_set("display_errors", 0);

const BASE_PATH = __DIR__ . '/../';
const SITE_URL = 'https://mqnqetsnz5.sharedwithexpose.com/';

require BASE_PATH . "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

require base_path('bootstrap.php');
