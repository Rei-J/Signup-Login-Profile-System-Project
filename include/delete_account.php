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
$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
$pwd = htmlspecialchars($_POST['pwd'], ENT_QUOTES, 'UTF-8');

try {
    require_once '../dbl.php';

    $stmt = $pdo->prepare('SELECT pwd FROM users WHERE name = :name AND email = :email');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && password_verify($pwd, $result['pwd'])) {
        $stmt = $pdo->prepare('DELETE FROM users WHERE name = :name AND email = :email');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        session_start();
        session_unset(); 
        session_destroy();
    } else {
        header('Location: ../index.php?deletion=failed');
        die();
    }
    
    $stmt = null;
    $pdo = null;
    unset($_SESSION['csrf_Token']);
    header('Location: ../login.php?deletion=success');
    die();
} catch (PDOException $e) {
    die('Query failed: '.$e->getMessage());
}