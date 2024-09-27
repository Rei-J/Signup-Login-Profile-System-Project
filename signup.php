<?php
    require_once 'config.php';
    require_once 'include/signup_view.php';
    
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
    <link rel="stylesheet" href="css/signup.css">
    <title>Sign Up</title>
</head>
<body>

<main>
    <h2>Sign Up</h2>
    <form action="include/inc.signup.php" method="post">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="name">

        <label for="lastname">Lastname</label>
        <input type="text" name="lastname" id="lastname" class="lastname">

        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="email">

        <label for="password">Password</label>
        <input type="password" name="pwd" id="pwd" class="pwd">

        <label for="gender">Gender</label>
        <div class="gender">
            Female<input type="radio" name="gender" id="female" class="female" value="female">
            <input type="radio" name="gender" id="male" class="male" value="male">Male
        </div>
  
        <label for="birthday">Birthday</label>
        <input type="date" name="birthday" id="birthday" class="birthday">

        <input type="hidden" name="csrf_Token" value="<?php echo htmlspecialchars($_SESSION["csrf_Token"], ENT_QUOTES, "UTF-8"); ?>">

        <button type="submit">Sign Up</button>
        <p>If you have an account? <a href="login.php">login</a></p>

    </form>

    <div class="error"><h2><?php signup_errors(); ?></h2></div>
</main>

</body>
</html>