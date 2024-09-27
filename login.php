<?php
    require_once 'config.php';
    require_once 'include/login_view.php';
    
    if(empty($_SESSION['csrf_Token'])){
        $_SESSION['csrf_Token'] = bin2hex(random_bytes(32));
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="icons/profile.png" type="image/x-icon">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/login.css">
    <title>Log In</title>
</head>
<body>

<main>
    <h2>Log In</h2>
    <h3><?php user(); ?></h3>
    <?php if(!isset($_SESSION['user_id'])){?>
        <form action="include/inc.login.php" method="post">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="email">

            <label for="password">Password</label>
            <input type="password" name="pwd" id="pwd" class="pwd">

            <input type="hidden" name="csrf_Token" value="<?php echo htmlspecialchars($_SESSION["csrf_Token"], ENT_QUOTES, "UTF-8"); ?>">
            <button>Log In</button>
            <p>If you don't have an account? <a href="signup.php">signup</a></p>
        </form>
    <?php }?>

    <div class="error"><h2><?php login_errors(); ?></h2></div>
</main>

</body>
</html>