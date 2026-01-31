<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;

try {
    $database = new Database();
$database->connect();
} catch (\Throwable $th) {
    echo $th->getMessage();
    exit;
}