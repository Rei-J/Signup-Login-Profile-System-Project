<?php
ini_set("session.use_only_cookies", 1);
ini_set("session.use_strict_mode", 1);

session_set_cookie_params([
    "lifetime" => 1800,
    "domain" => "localhost",
    "path" => "/",
    "secure" => true,
    "httponly" => true,
    "samesite" => "Strict"
]);

if(!session_start()){
    die("Session start failed!");
}

function session_regen_id(){
    session_regenerate_id(true);
    $_SESSION["last_regeneration"] = time();
}
function session_regen_id_loggedin(){
    session_regenerate_id(true);
    $userId = $_SESSION["user_id"];
    $newSessionId = session_create_id();
    $sessionId = $userId . "_" . $newSessionId;
    session_id($sessionId);
    $_SESSION["last_regeneration"] = time();
}

if (isset($_SESSION["user_id"])) {
    if (!isset($_SESSION["last_regeneration"])) {
        session_regen_id_loggedin();
    } else {
        $interval = 60 * 30;
        if(time() - $_SESSION["last_regeneration"] >= $interval){
            session_regen_id_loggedin();
        }
    }
    
} else {
    if (!isset($_SESSION["last_regeneration"])) {
        session_regen_id();
    } else {
        $interval = 60 * 30;
        if(time() - $_SESSION["last_regeneration"] >= $interval){
            session_regen_id();
        }
    }
}
