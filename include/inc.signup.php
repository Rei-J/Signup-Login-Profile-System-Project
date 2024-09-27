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
$lastname = htmlspecialchars($_POST['lastname'], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
$pwd = htmlspecialchars($_POST['pwd'], ENT_QUOTES, 'UTF-8');
$gender = htmlspecialchars($_POST['gender'], ENT_QUOTES, 'UTF-8');
$birthday = htmlspecialchars($_POST['birthday'], ENT_QUOTES, 'UTF-8');

try {
    require_once '../dbl.php';
    require_once 'signup_model.php';
    require_once 'signup_controller.php';

    $errors = [];

    if(empty_input($name, $lastname, $email, $pwd, $gender, $birthday)){
        $errors['empty_input'] = 'Fill all the inputs!';
    }
    if(user_exist($pdo, $name, $lastname)){
        $errors['user_exist'] = 'User already exist!';
    }
    if(invalid_email($email)){
        $errors['invalid_email'] = 'Invalid email!';
    }
    if(registered_email($pdo, $email)){
        $errors['registered_email'] = 'Email exist!';
    }

    if($errors){
        $_SESSION['signup_errors'] = $errors;
        $_SESSION['signup_data'] = ['name' => $name, 'email' => $email];
        header('Location: ../signup.php?signup=failed');
        die();
    }

    create_the_user($pdo, $name, $lastname, $email, $pwd, $gender, $birthday);

    $stmt = null;
    $pdo = null;
    unset($_SESSION['csrf_Token']);

    header('Location: ../login.php?signup=success');
    die();
} catch (PDOException $e) {
    die('Query Failed: '.$e->getMessage());
}
