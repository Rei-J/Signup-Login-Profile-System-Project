<?php
declare(strict_types=1);

function empty_login_inputs(string $email, string $pwd):bool {
    return empty($email) || empty($pwd);
}
function invalid_login_email(bool|array $result):bool {
    return !$result;
}
function invalid_pwd(string $pwd, string $hashedPwd):bool {
    return !password_verify($pwd, $hashedPwd);
}
