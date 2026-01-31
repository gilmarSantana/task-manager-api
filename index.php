<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;

try {
    $database = Database::getInstance();
    $database->connect();
} catch (\Throwable $th) {
    echo $th->getMessage();
    exit;
}