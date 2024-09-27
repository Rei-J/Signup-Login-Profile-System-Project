<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    error_log('Non-POST request attempted');
    exit();
}

require_once '../config.php';

if (!isset($_POST['csrf_Token']) || $_POST['csrf_Token'] !== $_SESSION['csrf_Token']) {
    die('CSRF TOKEN ERROR!');
}

$errors = []; // Array to collect errors

try {
    require_once '../dbl.php';
    $userId = $_SESSION['user_id']; // Get the logged-in user ID

    function isValidFileType($fileType, $allowedTypes) {
        return in_array($fileType, $allowedTypes);
    }

    function handleUploadedFile($file, $pdo, $userId, &$errors) {
        $allowed = array('jpg', 'jpeg', 'png', 'pdf');
        $fileName = $file['name']; // Original file name
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];

        $fileExt = explode(".", $fileName);
        $fileActualExt = strtolower(end($fileExt));

        if (isValidFileType($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 500000) {
                    $fileNewName = uniqid("", true) . "." . $fileActualExt; // Hashed file name
                    $fileDestination = "../upload_file/" . $fileNewName;

                    if (move_uploaded_file($fileTmpName, $fileDestination)) {
                        // Save the original file name in a session variable
                        $_SESSION['uploaded_files'][$fileNewName] = $fileName;

                        // Insert hashed file name into the database
                        $stmt = $pdo->prepare("INSERT INTO upload (file_name, file_size, user_id) VALUES (?, ?, ?)");
                        $stmt->execute([$fileNewName, $fileSize, $userId]);

                        // Redirect on success
                        header("Location: ../index.php?upload=success");
                        exit();
                    } else {
                        $errors['file_move'] = "There was an error moving your file!";
                    }
                } else {
                    $errors['file_size'] = "This file is too big!";
                }
            } else {
                $errors['file_error'] = "There was an error uploading your file!";
            }
        } else {
            $errors['file_type'] = "This type of file is not allowed!";
        }
    }

    // Call the file handling function
    handleUploadedFile($_FILES['file'], $pdo, $userId, $errors);

    // Check if there were any errors
    if (!empty($errors)) {
        $_SESSION['upload_errors'] = $errors; // Store errors in the session
        header("Location: ../index.php?upload=failed");
        exit();
    }

} catch (PDOException $e) {
    die('Query failed: ' . $e->getMessage());
}

