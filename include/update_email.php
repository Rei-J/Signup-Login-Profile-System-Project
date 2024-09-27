<?php

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    error_log('Non-POST request attempted');
    exit();
}

require_once '../config.php';

if(!isset($_POST['csrf_Token']) || $_POST['csrf_Token'] !== $_SESSION['csrf_Token']){
    die('CSRF TOKEN ERROR!');
}

$name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
$pwd = htmlspecialchars($_POST['pwd'], ENT_QUOTES, 'UTF-8');
$new_email = htmlspecialchars($_POST['new_email'], ENT_QUOTES, 'UTF-8');

try {
    require_once '../dbl.php';

    $stmt = $pdo->prepare('SELECT pwd FROM users WHERE name = :name');
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result && password_verify($pwd, $result['pwd'])){
        $stmt = $pdo->prepare('UPDATE users SET email = :new_email WHERE name = :name');
        $stmt->bindParam(':new_email', $new_email);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
    }else{
        header('Location: ../index.php?update=failed');
        die();
    }

    $stmt = null;
    $pdo = null;
    unset($_SESSION['csrf_Token']);
    header('Location: ../index.php?update=success');
    die();
} catch (PDOException $e) {
    die('Query failed: '.$e->getMessage());
}