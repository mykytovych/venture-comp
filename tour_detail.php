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

// Отримання tour_id з параметрів URL
$tour_id = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 0;

if ($tour_id === 0) {
    echo "Невірний ID туру.";
    exit;
}

// Запит до бази даних для отримання інформації про конкретний тур
try {
    $stmt = $pdo->prepare("
        SELECT tours.*, GROUP_CONCAT(tags.name SEPARATOR ', ') as tags, GROUP_CONCAT(tags.icon_url SEPARATOR ', ') as tag_icons
        FROM tours
        LEFT JOIN tour_tags ON tours.tour_id = tour_tags.tour_id
        LEFT JOIN tags ON tour_tags.tag_id = tags.tag_id
        WHERE tours.tour_id = :tour_id
        GROUP BY tours.tour_id
    ");

    $user_id = $_SESSION['user_id'];

    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->execute();
    $tour = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tour) {
        // Обробка випадку, якщо тур з вказаним ID не знайдено
        echo "Тур не знайдено.";
        exit;
    }

    // Запит до бази даних для отримання відгуків
    $reviews_stmt = $pdo->prepare("SELECT reviews.*, users.first_name FROM reviews LEFT JOIN users ON reviews.user_id = users.user_id WHERE reviews.tour_id = :tour_id ORDER BY reviews.created_at DESC");
    $reviews_stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $reviews_stmt->execute();
    $reviews = $reviews_stmt->fetchAll(PDO::FETCH_ASSOC);

    // HTML для відображення інформації про тур
    echo '
    <!DOCTYPE html>
    <html lang="uk">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . htmlspecialchars($tour['title']) . '</title>
        <link rel="icon" type="image/x-icon" href="travells-favicon-color.png">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="style.css">
        <style>
            .tour-image {
                max-width: 100%;
                height: auto;
                margin-bottom: 20px;
            }
            .tag-image {
                max-width: 30px;
                height: auto;
                cursor: pointer;
            }
            .tour-header {
                margin-top: 20px;
                margin-bottom: 20px;
            }
            body {
                padding-top: 70px; /* Відступ для фіксованого хедера */
            }
            header {
                background-color: #f55105;
                position: fixed;
                top: 0;
                width: 100%;
                z-index: 100;
            }
            footer {
                padding: 20px 0;
                background-color: #f55105;
                color: #fff;
                text-align: center;
                width: 100%;
                position: absolute;
                bottom: 0;
            }
            .order-button {
                margin-top: 20px;
                background-color: #f55105;
                color: white;
                border: none;
                padding: 10px 20px;
                font-size: 16px;
                cursor: pointer;
            }
            .order-button:hover {
                background-color: #FF7A29;
            }
            .review-section {
                margin-top: 50px;
            }
            .review {
                margin-bottom: 30px;
            }
            .review p {
                margin: 0;
            }
            .admin-buttons {
                display: flex;
                gap: 10px;
                margin-top: 20px;
            }
            .admin-button {
                background: none;
                border: none;
                cursor: pointer;
            }
            .admin-button img {
                width: 24px;
                height: 24px;
            }
        </style>
    </head>
    <body>
    <header>
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
                            <a class="nav-link" href="tours.php"  style="color: #FFFEFA;">Тури</a>
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
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="tour-header">' . htmlspecialchars($tour['title']) . '</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <img src="' . htmlspecialchars($tour['image']) . '" alt="' . htmlspecialchars($tour['title']) . '" class="img-fluid tour-image">
            </div>
            <div class="col-md-6">
                <p><b>Клас:</b> ' . htmlspecialchars($tour['class']) . '</p>
                <p><b>Місце проведення:</b> ' . htmlspecialchars($tour['country']) . ', ' . htmlspecialchars($tour['city']) . '</p>
                <p><b>Кількість місць:</b> ' . htmlspecialchars($tour['available_seats']) . '</p>
                <p><b>Ціна:</b> ' . htmlspecialchars($tour['price']) . ' грн</p>
                <p><b>Опис:</b></p>
                <p>' . htmlspecialchars($tour['detailed_description']) . '</p>
                <p><b>Теги:</b></p>
                <div>';

    // Перевіряємо наявність тегів
    if (!empty($tour['tags'])) {
        $tag_names = explode(', ', $tour['tags']);
        $tag_icons = explode(', ', $tour['tag_icons']);
        foreach ($tag_names as $index => $tag_name) {
            $icon_url = $tag_icons[$index];
            echo '<img src="' . htmlspecialchars($icon_url) . '" alt="' . htmlspecialchars($tag_name) . '" class="img-fluid tag-image" data-bs-toggle="tooltip" data-bs-placement="bottom" title="' . htmlspecialchars($tag_name) . '">';
        }
    } else {
        echo '<p>Немає тегів</p>';
    }

    echo '
                </div>
                <a href="add_to_cart.php?tour_id=' . htmlspecialchars($tour['tour_id']) . '" class="btn order-button">Замовити</a>';

    // Відображення кнопок редагування та видалення тільки для адміністратора
    if ($user['is_admin']) {
        echo '
                <div class="admin-buttons">
                    <a href="edit_tour.php?tour_id=' . htmlspecialchars($tour['tour_id']) . '" class="admin-button" target="_blank">
                        <img src="https://www.svgrepo.com/show/521132/edit-2.svg" alt="Редагувати">
                    </a>
                    <a href="delete_tour.php?tour_id=' . htmlspecialchars($tour['tour_id']) . '" class="admin-button">
                        <img src="https://www.svgrepo.com/show/502614/delete.svg" alt="Видалити">
                    </a>
                </div>';
    }

    echo '
            </div>
        </div>
        
        <div class="review-section">
            <h2>Відгуки</h2>';

    // Відображення відгуків
    if (count($reviews) > 0) {
        foreach ($reviews as $review) {
            echo '
            <div class="review">
                <p><strong>' . htmlspecialchars($review['first_name']) . ':</strong> <span>' . str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) . '</span></p>
                <p>' . htmlspecialchars($review['comment']) . '</p>
                <p><small>' . htmlspecialchars($review['created_at']) . '</small></p>
            </div>';
        }
    } else {
        echo '<p>Немає відгуків.</p>';
    }

    // Форма для додавання відгуку
    echo '
            <form action="add_review.php" method="POST">
                <input type="hidden" name="tour_id" value="' . htmlspecialchars($tour['tour_id']) . '">
                <div class="mb-3">
                    <label for="rating" class="form-label">Оцінка:</label>
                    <select class="form-select" name="rating" id="rating" required>
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="comment" class="form-label">Коментар:</label>
                    <textarea class="form-control" name="comment" id="comment" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn" style="background-color: #f55105; color: #FFFEFA;">Додати відгук</button><br><br>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        // Ініціалізація popper для тегів
        var tooltipTriggerList = [].slice.call(document.querySelectorAll(\'[data-bs-toggle="tooltip"]\'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    </script>
    </body>
    </html>';

} catch (PDOException $e) {
    // Обробка помилки
    echo "Error: " . $e->getMessage();
}
?>
