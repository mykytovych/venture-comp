<?php
require 'db_connect.php'; 

session_start();


$inactive = 1800;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

$_SESSION['last_activity'] = time(); 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user['is_admin']) {
    echo "У вас немає прав для видалення турів.";
    exit();
}

$tour_id = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 0;

if ($tour_id === 0) {
    echo "Невірний ID туру.";
    exit();
}

try {
    // Видалення відгуків, пов'язаних з туром
    $stmt_reviews = $pdo->prepare("DELETE FROM reviews WHERE tour_id = :tour_id");
    $stmt_reviews->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt_reviews->execute();

    // Видалення туру з таблиці tours
    $stmt_tour = $pdo->prepare("DELETE FROM tours WHERE tour_id = :tour_id");
    $stmt_tour->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt_tour->execute();

    echo "Тур успішно видалено.";
    header("Location: tours.php"); 
    exit();
} catch (PDOException $e) {
    echo "Помилка: " . $e->getMessage();
}
?>
