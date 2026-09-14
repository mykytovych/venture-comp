<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Перевірка наявності обов'язкових даних
    if (!empty($_POST['tour_id']) && !empty($_POST['rating']) && !empty($_POST['comment'])) {
        $tour_id = (int)$_POST['tour_id'];
        $rating = (int)$_POST['rating'];
        $comment = $_POST['comment'];

        // Отримання user_id з сесії
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            try {
                // Вставка відгуку в базу даних
                $stmt = $pdo->prepare("INSERT INTO reviews (tour_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
                $stmt->execute([$tour_id, $user_id, $rating, $comment]);

                // Переадресація назад на сторінку туру
                header("Location: tour_detail.php?tour_id=$tour_id");
                exit();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Помилка: користувач не залогінений.";
        }
    } else {
        echo "Всі поля є обов'язковими.";
    }
}
?>
