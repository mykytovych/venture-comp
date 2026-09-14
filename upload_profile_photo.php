<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo'])) {
    $file = $_FILES['profile_photo'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '';
        $uploadPath = $uploadDir . basename($file['name']);
        $fileType = pathinfo($uploadPath, PATHINFO_EXTENSION);

        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (!in_array(strtolower($fileType), $allowedTypes)) {
            echo "Дозволені тільки JPG, JPEG, PNG або GIF файлів.";
            exit();
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {

            $stmt = $pdo->prepare("UPDATE users SET profile_photo = :profile_photo WHERE user_id = :user_id");
            $stmt->execute([
                'profile_photo' => $uploadPath,
                'user_id' => $user_id
            ]);

            $_SESSION['profile_photo'] = $uploadPath; 
            header("Location: PA.php");
            exit();
        } else {
            echo "Помилка при завантаженні файлу.";
            exit();
        }
    } else {
        echo "Помилка: " . $file['error'];
        exit();
    }
} else {
    header("Location: PA.php");
    exit();
}
?>
