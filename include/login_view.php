<?php
declare(strict_types=1);

function login_errors(){
    if (isset($_SESSION['login_errors'])) {
        $errors = $_SESSION['login_errors'];
        echo '<br>';
        foreach($errors as $error){
            echo $error.'<br>';
        }
        unset($_SESSION['login_errors']);
    } else {
        if(isset($_GET['login']) && $_GET['login'] === 'success'){
            echo 'Login Success!';
        }
    }
}

function user(){
    if(isset($_SESSION['user_id'])){
        echo 'You are loggedin as ' . $_SESSION['user_email'];
    }else{
        echo 'You are not loggedin!';
    }
}