<?php
declare(strict_types=1);

function signup_errors(){
    if (isset($_SESSION['signup_errors'])) {
        $errors = $_SESSION['signup_errors'];
        echo '<br>';
        foreach($errors as $error){
            echo $error.'<br>';
        }
        unset($_SESSION['signup_errors']);
    } else {
        if(isset($_GET['signup']) && $_GET['signup'] === 'success'){
            echo 'Sign Up success!';
        }
    }
}
