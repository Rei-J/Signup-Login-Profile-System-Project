<?php
$dbl = "mysql:host=localhost;dbname=liveserver;";
$dbusername = "root";
$dbpassword = "";

try {
    $pdo = new PDO($dbl, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection Failed: ".$e->getMessage());
}