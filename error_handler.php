<?php
error_log(E_ALL);
ini_set("display_errors", 0);

function customErrorHandler($errno, $errstr, $errfile, $errline){
    $message = "Errors: [$errno]$errstr - $errfile:$errline";
    error_log($message . PHP_EOL, 3, "error_handler.txt");
}

set_error_handler("customErrorHandler");