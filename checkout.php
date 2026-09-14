<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

require 'db_connect.php';

$cart_items = $_SESSION['cart'];
$tours = [];
if (!empty($cart_items)) {
    $placeholders = implode(',', array_fill(0, count($cart_items), '?'));
    $stmt = $pdo->prepare("SELECT * FROM tours WHERE tour_id IN ($placeholders)");
    $stmt->execute($cart_items);
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get user data
$user_id = $_SESSION['user_id'];
$user_stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$user_stmt->execute([$user_id]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

// Обробка форми замовлення
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Логіка обробки замовлення (збереження у базу даних)
    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, tour_id, booking_date, status, payment_status) VALUES (?, ?, NOW(), 'В опрацюванні...', 'Неоплачений')");
    foreach ($cart_items as $tour_id) {
        $stmt->execute([$user_id, $tour_id]);
    }
    
    // Очистка кошика після успішного замовлення
    $_SESSION['cart'] = [];
    header('Location: thank_you.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформлення замовлення</title>
    <link rel="icon" type="image/x-icon" href="travells-favicon-color.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        img {
            cursor: pointer;
        }
    </style>
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
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="mainpage.php" style="color: #FFFEFA">Головна</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tours.php" style="color: #FFFEFA">Тури</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                        <img src="cart.svg" style="max-width: 30px; height: auto" alt="Кошик">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<br>
<div class="col-md-6 offset-md-2" style="margin-top: 100px; margin-bottom: 20px;">
    <h1 style="font-size: 92px;"><b>Оформлення замовлення</b></h1>
</div>
<div class="container">
    <form method="post" action="checkout.php">
        <div class="row">
            <?php foreach ($tours as $tour) : ?>
                <div class="col-md-4">
                    <div class="card border border-secondary mb-3">
                        <div class="card-body">
                            <img src="<?= htmlspecialchars($tour['image']) ?>" alt="<?= htmlspecialchars($tour['title']) ?>" class="img-fluid tour-image">
                            <h3 class="card-text"><?= htmlspecialchars($tour['title']) ?></h3>
                            <p>Ціна: <?= htmlspecialchars($tour['price']) ?> грн</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="form-group mb-3">
                    <label for="first_name">Ім'я:</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="last_name">Прізвище:</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="birthdate">Дата народження:</label>
                    <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?= htmlspecialchars($user['birthdate']) ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="address">Адреса:</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="card_number">Номер банківської карти:</label>
                    <input type="text" class="form-control" id="card_number" name="card_number" required>
                </div>
                <div class="form-group mb-3">
                    <label for="card_expiry">Термін дії карти:</label>
                    <input type="text" class="form-control" id="card_expiry" name="card_expiry" placeholder="MM/YYYY" required>
                </div>
                <div class="form-group mb-3">
                    <label for="card_cvv">CVV:</label>
                    <input type="password" class="form-control" id="card_cvv" name="card_cvv" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn custom-button" style="background-color: #f55105; color: #FFFEFA; width: 20%;">Підтвердити замовлення</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>
