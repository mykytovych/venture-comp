<?php
session_start();

// Встановити час активності сесії (30 хвилин)
$inactive = 1800;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

$_SESSION['last_activity'] = time(); // оновлення часу останньої активності

// Перевірити, чи користувач увійшов
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Підключення до бази даних
require 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Отримати дані користувача з бази даних
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Користувача не знайдено.";
    exit();
}

// Отримати історію бронювань користувача
$bookings_stmt = $pdo->prepare("
    SELECT bookings.*, tours.title, tours.image
    FROM bookings
    JOIN tours ON bookings.tour_id = tours.tour_id
    WHERE bookings.user_id = :user_id
    ORDER BY bookings.booking_date DESC
");
$bookings_stmt->execute(['user_id' => $user_id]);
$bookings = $bookings_stmt->fetchAll(PDO::FETCH_ASSOC);

$profile_photo = $user['profile_photo'] ?: 'default_photo.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Особистий кабінет</title>
    <link rel="icon" type="image/x-icon" href="travells-favicon-color (1).png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header style="background-color: #f55105; position: fixed; z-index: 100; width: 100%;">
        <nav class="navbar navbar-expand-lg oswald-font">
            <div class="container-fluid">
                <a class="navbar-brand" href="mainpage.php" style="color: #FFFEFA;">
                    <img src="travells-favicon-color.png" alt="Турагенство" style="width: 50px; height: 50px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <img style="height: 70px;" src="exit.svg" alt="Вийти">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <br>
    <div class="container" style="margin-top: 100px;">
        <div class="row">
            <div class="col-4 text-center">
                <img src="<?= htmlspecialchars($profile_photo) ?>" alt="pfp" class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
                <h2><?= htmlspecialchars($user['first_name']) . ' ' . htmlspecialchars($user['last_name']) ?></h2>
                <p><?= htmlspecialchars($user['email']) ?></p>
                <p><?= htmlspecialchars($user['birthdate']) ?></p>
                <?php if ($user['is_admin']): ?>
                    <p><strong>Адміністратор</strong></p>
                <?php endif; ?>
                <form action="upload_profile_photo.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="profile_photo" accept="image/*" class="form-control">
                    <button type="submit" class="btn mt-2" style="background-color: #f55105; color: #FFFEFA;">Змінити фото</button>
                </form>
                <ul class="list-group list-group-flush raleway-font mt-3">
                    <li class="list-group-item">Мої відгуки</li>
                    <li class="list-group-item">Мої дані</li>
                    <li class="list-group-item">FAQ</li>
                    <li class="list-group-item">Служба підтримки</li>
                </ul>
            </div>
            <div class="col-8">
                <h2 style="font-size: 36px;">ІСТОРІЯ ТУРІВ</h2>
                <?php if (count($bookings) > 0): ?>
                    <div class="row">
                        <?php foreach ($bookings as $booking): ?>
                            <div class="col-md-4">
                                <div class="card mb-4">
                                    <img src="<?= htmlspecialchars($booking['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($booking['title']) ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($booking['title']) ?></h5>
                                        <p class="card-text"><strong>Дата бронювання:</strong> <?= htmlspecialchars($booking['booking_date']) ?></p>
                                        <p class="card-text"><strong>Статус:</strong> <?= htmlspecialchars($booking['status']) ?></p>
                                        <p class="card-text"><strong>Статус оплати:</strong> <?= htmlspecialchars($booking['payment_status']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Немає бронювань.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class="text-white text-center" style="padding: 20px 0; background-color: #f55105; margin-top: 10%;">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4">
                    <p>Про компанію</p>
                </div>
                <div class="col-12 col-md-4">
                    <p>Наші партнери</p>
                </div>
                <div class="col-12 col-md-4">
                    <p>Працевлаштування</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <p style="font-size: 14px;">Copyright © Всі права захищені</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    </script>
</body>
</html>
