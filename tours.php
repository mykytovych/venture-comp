<?php
require 'db_connect.php'; // Підключення до бази даних

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

// Отримання значення пошуку
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Отримання списку турів з урахуванням фільтрів
try {
    $query = "SELECT * FROM tours WHERE title LIKE :search";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['search' => '%' . $search . '%']);
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Обробка помилки
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тури</title>
    <link rel="icon" type="image/x-icon" href="travells-favicon-color.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        .tour-image {
            width: 100%;
            height: auto;
        }
        .tag-image {
            width: 48px;
            height: 48px;
            margin-right: 5px;
            margin-top: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<header style="background-color: #f55105; position: fixed; z-index: 100;  width: 100%;">
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
                        <a class="nav-link disabled" style="color: #fffefa92;">Тури</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="PA.php">
                            <img src="person.svg" alt="Особистий кабінет">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<br>
<div class="col-md-6 offset-md-2" style="margin-top: 100px; margin-bottom: 20px;">
    <h1 style="font-size: 92px;"><b>СПИСОК ТУРІВ</b> <?php if ($user['is_admin']): ?> <a href="tours_admin.php" class="admin-button"> <img src="https://www.svgrepo.com/show/502926/add-note.svg" style="heigh: 10%; width: 10%" alt="Додати тур"></a> </h1>
    <?php endif; ?>

</div>
<div class="container">
    <div class="row">
        <div class="col-2">
            <!-- Фільтри і пошук -->
            <form id="filter-form" method="GET">
                <div class="mb-3">
                    <label for="search" class="form-label">Пошук по назві:</label>
                    <input type="text" class="form-control" id="search" name="search" value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="mb-3">
                    <label for="class" class="form-label">Клас:</label>
                    <select class="form-select" id="class" name="class">
                        <option value="">Всі</option>
                        <option value="C">C</option>
                        <option value="B">B</option>
                        <option value="A">A</option>
                        <option value="S">S</option>
                        <option value="S+">S+</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="country" class="form-label">Країна:</label>
                    <input type="text" class="form-control" id="country" name="country">
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">Місто:</label>
                    <input type="text" class="form-control" id="city" name="city">
                </div>
                <div class="mb-3">
                    <label for="price_min" class="form-label">Мінімальна ціна:</label>
                    <input type="number" class="form-control" id="price_min" name="price_min">
                </div>
                <div class="mb-3">
                    <label for="price_max" class="form-label">Максимальна ціна:</label>
                    <input type="number" class="form-control" id="price_max" name="price_max">
                </div>

                <button type="button" id="apply-filters" class="btn" style="background-color: #f55105; color: #FFFEFA;">Застосувати фільтри</button>
            </form>
        </div>
        <div class="col-10" id="tours-container">
            <!-- Карточки турів будуть додаватися тут -->
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center">
            <button id="load-more" class="btn custom-button" style="background-color: #f55105; color: #FFFEFA; height: 75%;">Завантажити ще</button>
        </div>
    </div>
</div>
<footer class="text-white text-center" style="padding: 20px 0; background-color: #f55105;">
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
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybbyQDfYl6lW/4pJ2zvXPkeDd5wjn2t9TBTXcVO9C9REq4p3E" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    let offset = 0;
    const limit = 3; // Кількість турів, які завантажуються одночасно

    function loadTours(filters = {}) {
        $.ajax({
            url: 'load_tours.php',
            type: 'GET',
            data: {
                offset: offset,
                limit: limit,
                search: filters.search || '',
                class: filters.class || '',
                country: filters.country || '',
                city: filters.city || '',
                price_min: filters.price_min || '',
                price_max: filters.price_max || '',
                hide_no_seats: filters.hide_no_seats || false
            },
            success: function(response) {
                if (offset === 0) {
                    $('#tours-container').html(response);
                } else {
                    $('#tours-container').append(response);
                }
                offset += limit;

                // Initialize Popper.js for new tags
                $('[data-bs-toggle="tooltip"]').each(function() {
                    new bootstrap.Tooltip(this);
                });

                // Приховати кнопку "Завантажити ще" якщо більше немає турів
                if (!response.trim()) {
                    $('#load-more').hide();
                } else {
                    $('#load-more').show();
                }
            }
        });
    }
    $('#load-more').on('click', function() {
        const filters = {
            search: $('#search').val(),
            class: $('#class').val(),
            country: $('#country').val(),
            city: $('#city').val(),
            price_min: $('#price_min').val(),
            price_max: $('#price_max').val(),
            hide_no_seats: $('#hide_no_seats').is(':checked')
        };
        loadTours(filters);
    });

    $('#apply-filters').on('click', function() {
        offset = 0;
        const filters = {
            search: $('#search').val(),
            class: $('#class').val(),
            country: $('#country').val(),
            city: $('#city').val(),
            price_min: $('#price_min').val(),
            price_max: $('#price_max').val(),
            hide_no_seats: $('#hide_no_seats').is(':checked')
        };
        loadTours(filters);
    });

    // Завантажити перші тури при завантаженні сторінки
    loadTours();
});
</script>
</body>
</html>

   
