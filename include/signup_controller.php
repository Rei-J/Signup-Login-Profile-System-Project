<?php
declare(strict_types=1);

function empty_input(string $name, string $lastname, string $email, string $pwd, string $gender, string $birthday):bool {
    return empty($name) || empty($lastname) || empty($email) || empty($pwd) || empty($gender) || empty($birthday);
}
function user_exist(object $pdo, string $name, string $lastname):bool {
    return get_user_data($pdo, $name, $lastname) !== false;
}
function invalid_email(string $email):bool {
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
}
function registered_email(object $pdo, string $email):bool {
    return get_the_email($pdo, $email) !== false;
}

function create_the_user(object $pdo, string $name, string $lastname, string $email, string $pwd, string $gender, string $birthday){
    insert_the_data($pdo, $name, $lastname, $email, $pwd, $gender, $birthday);
}