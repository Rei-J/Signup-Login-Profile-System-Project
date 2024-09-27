<?php
declare(strict_types=1);
function uploadError(){
    if (isset($_SESSION['upload_errors'])) {
        $errors = $_SESSION['upload_errors'];
        foreach ($errors as $error) {
            echo $error.'<br>';
        }
        unset($_SESSION['upload_errors']); // Clear errors after displaying
    } else {
        if(isset($_GET['upload']) && $_GET['upload'] === 'success'){
            echo 'Upload success!';
        }
    }
}
