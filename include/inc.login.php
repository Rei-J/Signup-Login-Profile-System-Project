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

$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
$pwd = htmlspecialchars($_POST['pwd'], ENT_QUOTES, 'UTF-8');

try {
    require_once '../dbl.php';
    require_once 'login_model.php';
    require_once 'login_controller.php';

    $errors = [];

    if(empty_login_inputs($email, $pwd)){
        $errors['empty_login_inputs'] = 'Fill all inputs!';
    }

    $result = get_data($pdo, $email); 

    if(invalid_login_email($result)){
        $errors['invalid_login_email'] = 'Invalid email!';
    }
    if(!invalid_login_email($result) && invalid_pwd($pwd, $result['pwd'])){
        $errors['invalid_pwd'] = 'Invalid password!';
    }

    if($errors){
        $_SESSION['login_errors'] = $errors;
        header('Location: ../login.php?login=failed');
        die();
    }

    $newSessionId = session_create_id();
    $sessionId = $newSessionId . "_" . $result['id'];
    session_id($sessionId);

    $_SESSION['user_id'] = $result['id'];
    $_SESSION['user_email'] = htmlspecialchars($result['email']);
    $_SESSION['last_regeneration'] = time();

    $stmt = null;
    $pdo = null;
    unset($_SESSION['csrf_Token']);
    header('Location: ../index.php?login=success');
    die();

} catch (PDOException $e) {
    die('Query Failed: '.$e->getMessage());
}