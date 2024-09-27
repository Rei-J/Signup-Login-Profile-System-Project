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
$current_pwd = htmlspecialchars($_POST['current_pwd'], ENT_QUOTES, 'UTF-8');
$new_pwd = htmlspecialchars($_POST['new_pwd'], ENT_QUOTES, 'UTF-8');

try {
    require_once '../dbl.php';

    $stmt = $pdo->prepare('SELECT pwd FROM users WHERE name = :name');
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result && password_verify($current_pwd, $result['pwd'])){
        $hashedPwd = password_hash($new_pwd, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare('UPDATE users SET pwd = :new_pwd WHERE name = :name');
        $stmt->bindParam(':new_pwd', $hashedPwd);
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