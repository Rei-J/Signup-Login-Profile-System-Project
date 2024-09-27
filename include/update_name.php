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

$current_name = htmlspecialchars($_POST['current_name'], ENT_QUOTES, 'UTF-8');
$new_name = htmlspecialchars($_POST['new_name'], ENT_QUOTES, 'UTF-8');
$pwd = htmlspecialchars($_POST['pwd'], ENT_QUOTES, 'UTF-8');

try {
    require_once '../dbl.php';

    $stmt = $pdo->prepare('SELECT pwd FROM users WHERE name = :current_name');
    $stmt->bindParam(':current_name', $current_name);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result && password_verify($pwd, $result['pwd'])){
        $stmt = $pdo->prepare('UPDATE users SET name = :new_name WHERE name = :current_name');
        $stmt->bindParam(':new_name', $new_name);
        $stmt->bindParam(':current_name', $current_name);
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