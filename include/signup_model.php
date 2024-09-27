<?php
declare(strict_types=1);

function get_user_data(object $pdo, string $name, string $lastname){
    $stmt = $pdo->prepare("SELECT name, lastname FROM users WHERE name = :name AND lastname = :lastname");
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":lastname", $lastname);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function get_the_email(object $pdo, string $email){
    $stmt = $pdo->prepare("SELECT email FROM users WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insert_the_data(object $pdo, string $name, string $lastname, string $email, string $pwd, string $gender, string $birthday){
    $stmt = $pdo->prepare("INSERT INTO users (name, lastname, email, pwd, gender, birthday) VALUES (:name, :lastname, :email, :pwd, :gender, :birthday)");
    $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT, ["cost" => 12]);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":lastname", $lastname);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":pwd", $hashedPwd);
    $stmt->bindParam(":gender", $gender);
    $stmt->bindParam(":birthday", $birthday);
    $stmt->execute();
}