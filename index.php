<?php
    require_once 'error_handler.php';
    require_once 'config.php';
    require_once 'dbl.php';
    require_once 'include/login_view.php';
    require_once 'include/upload_view.php';

    $userId = $_SESSION['user_id'];

    if(!isset($userId)){
        header('Location: login.php');
        die();
    }
    if(empty($_SESSION['csrf_Token'])){
        $_SESSION['csrf_Token'] = bin2hex(random_bytes(32));
    }


/* ------------------------------------------------------------------------------------------------ */
    $stmt = $pdo->prepare("SELECT * FROM upload WHERE user_id = ? ORDER BY uploaded_at DESC");
    $stmt->execute([$userId]);  // Pass the user_id to filter results
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
/* ------------------------------------------------------------------------------------------------ */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="shortcut icon" href="icons/profile.png" type="image/x-icon">
    <title>Live Server</title>
</head>
<body>

<header id="header" class="header">
    <img src="icons/logo.png" alt="user profile">
    <h3><?php user(); ?></h3>
    <div class="toggle-button" onclick="toggleSetting()"></div>
    <aside class="nav-sidebar">
        <h3>Log Out</h3>
        <form action="include/inc.logout.php" method="post">
            <button>Log Out</button>
        </form><br>
        <h3>Settings</h3>
        <section id="update" class="update">
            <h3>Update Name</h3>
            <form action="include/update_name.php" method="post">
                <label for="current_name">Current Name:</label>
                <input type="text" id="current_name" name="current_name" class="current_name">
        
                <label for="new_name">New Name:</label>
                <input type="text" id="new_name" name="new_name" class="new_name">
        
                <label for="pwd">Password:</label>
                <input type="password" id="pwd" name="pwd" class="pwd">

                <input type="hidden" name="csrf_Token" value="<?php echo htmlspecialchars($_SESSION["csrf_Token"], ENT_QUOTES, "UTF-8"); ?>">
                <button type="submit">Update</button>
            </form>
            <h3>Update Email</h3>
            <form action="include/update_email.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="name">
        
                <label for="pwd">Password:</label>
                <input type="password" id="pwd" name="pwd" class="pwd">

                <label for="new_email">New Email:</label>
                <input type="email" id="new_email" name="new_email" class="new_email">

                <input type="hidden" name="csrf_Token" value="<?php echo htmlspecialchars($_SESSION["csrf_Token"], ENT_QUOTES, "UTF-8"); ?>">
                <button type="submit">Update</button>
            </form>
            <h3>Update Password</h3>
            <form action="include/update_password.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="name">
        
                <label for="current_pwd">Current Password:</label>
                <input type="password" id="current_pwd" name="current_pwd" class="current_pwd">

                <label for="new_pwd">New Password:</label>
                <input type="password" id="new_pwd" name="new_pwd" class="new_pwd">
                <input type="hidden" name="csrf_Token" value="<?php echo htmlspecialchars($_SESSION["csrf_Token"], ENT_QUOTES, "UTF-8"); ?>">
                <button type="submit">Update</button>
            </form>
            <h3>Delete Account</h3>
            <form action="include/delete_account.php" method="post">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="name">

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="email">

                <label for="password">Password:</label>
                <input type="password" name="pwd" id="pwd" class="pwd">

                <input type="hidden" name="csrf_Token" value="<?php echo htmlspecialchars($_SESSION["csrf_Token"], ENT_QUOTES, "UTF-8"); ?>">
                <button>Delete</button>
            </form>
        </section>
    </aside>
</header>

<main>
    <div id="time" class="time"></div>
    <h3>Calculator</h3>
    <form>
        <label for="value1">First value</label>
        <input type="number" name="value1" id="value1" class="value1">
        <label for="value2">Second value</label>
        <input type="number" name="value2" id="value2" class="value2">
        <select name="oparator" id="oparator">
            <option value="add">+</option>
            <option value="sub">-</option>
            <option value="mult">*</option>
            <option value="div">/</option>
        </select>
        <button type="button" onclick="calc()">Calculate</button>
    </form>

    <div id="result" class="result"></div>
   
</main>

<section class="upload_img">
    <h3>Upload the image</h3>
    <form action="include/upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" class="uplfile">

        <input type="hidden" name="csrf_Token" value="<?php echo htmlspecialchars($_SESSION["csrf_Token"], ENT_QUOTES, "UTF-8"); ?>">
        
        <input type="submit" name="submit" class="uplimg" value="Upload Image">
    </form>

    <div class="upload_error"><p><?php uploadError(); ?></p></div>
</section>

<div class="title">
    <h3>Images</h3>
</div>
<section class="display_img">
    <?php if (!empty($images)): ?>
        <?php foreach ($images as $image): ?>
            <div class="img">
                <img src="upload_file/<?= htmlspecialchars($image['file_name']) ?>" alt="Image" width="200">

                <?php
                // Retrieve the original file name from the session
                $hashedFileName = $image['file_name'];
                
                // Check if the hashed name exists in the session; handle missing names gracefully
                if (isset($_SESSION['uploaded_files'][$hashedFileName])) {
                    $originalFileName = $_SESSION['uploaded_files'][$hashedFileName];

                    // Remove file extension
                    $fileNameWithoutExt = pathinfo($originalFileName, PATHINFO_FILENAME);
                } else {
                    // Default in case the original file name is not found
                    $fileNameWithoutExt = 'Unknown';
                }
                ?>

                <p>File Name: <?= htmlspecialchars($fileNameWithoutExt) ?></p>
                <p>File Size: <?= htmlspecialchars($image['file_size']) ?> Bytes</p>

                <form method="post" action="include/delete_image.php">
                    <input type="hidden" name="file_name" value="<?= htmlspecialchars($image['file_name']) ?>">
                    <input type="hidden" name="csrf_Token" value="<?php echo htmlspecialchars($_SESSION["csrf_Token"], ENT_QUOTES, "UTF-8"); ?>">
                    <button type="submit" class="delete_img">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No images uploaded yet.</p>
    <?php endif; ?>
</section>


</body>
<script src="js/setting.js"></script>
</html>