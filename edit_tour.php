<?php
session_start();
require 'db_connect.php';

// Перевірити, чи користувач є адміністратором
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: PA.php");
    exit();
}

if (!isset($_GET['tour_id'])) {
    header("Location: tours_admin.php");
    exit();
}

$tour_id = $_GET['tour_id'];

// Отримання даних про тур
$stmt = $pdo->prepare("SELECT * FROM tours WHERE tour_id = :tour_id");
$stmt->execute([':tour_id' => $tour_id]);
$tour = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tour) {
    $_SESSION['message'] = "Тур не знайдено!";
    header("Location: tours_admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $class = $_POST['class'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $available_seats = $_POST['available_seats'];
    $price = $_POST['price'];
    $short_description = $_POST['short_description'];
    $detailed_description = $_POST['detailed_description'] ?? null;
    $tags = $_POST['tags'] ?? [];

    // Завантаження зображення
    if (!empty($_FILES['image']['name'])) {
        $image = basename($_FILES['image']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $image;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_uploaded = true;
        } else {
            $_SESSION['message'] = "Помилка при завантаженні зображення.";
        }
    } else {
        $image = $_POST['existing_image'];
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE tours
            SET title = :title, class = :class, country = :country, city = :city, available_seats = :available_seats, price = :price, short_description = :short_description, detailed_description = :detailed_description, image = :image
            WHERE tour_id = :tour_id
        ");
        $stmt->execute([
            ':title' => $title,
            ':class' => $class,
            ':country' => $country,
            ':city' => $city,
            ':available_seats' => $available_seats,
            ':price' => $price,
            ':short_description' => $short_description,
            ':detailed_description' => $detailed_description,
            ':image' => $image,
            ':tour_id' => $tour_id
        ]);

        // Оновлення тегів
        $stmt = $pdo->prepare("DELETE FROM tour_tags WHERE tour_id = :tour_id");
        $stmt->execute([':tour_id' => $tour_id]);

        foreach ($tags as $tag_id) {
            $stmt = $pdo->prepare("
                INSERT INTO tour_tags (tour_id, tag_id)
                VALUES (:tour_id, :tag_id)
            ");
            $stmt->execute([
                ':tour_id' => $tour_id,
                ':tag_id' => $tag_id
            ]);
        }

        $_SESSION['message'] = "Тур успішно оновлено!";
        header("Location: tours_admin.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['message'] = "Помилка під час оновлення туру: " . $e->getMessage();
    }
}

// Отримання списку тегів
$tags_stmt = $pdo->query("SELECT * FROM tags");
$tags = $tags_stmt->fetchAll(PDO::FETCH_ASSOC);

// Отримання тегів для поточного туру
$tour_tags_stmt = $pdo->prepare("SELECT tag_id FROM tour_tags WHERE tour_id = :tour_id");
$tour_tags_stmt->execute([':tour_id' => $tour_id]);
$tour_tags = $tour_tags_stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагування туру</title>
    <link rel="icon" type="image/x-icon" href="travells-favicon-color (1).png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="gradient-custom-4">
  <section class="vh-100">
    <div class="container py-5 h-100">
      <div class="row justify-content-center align-items-center h-100">
        <div class="col-12 col-lg-9 col-xl-7">
          <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
            <div class="card-body p-4 p-md-5">
              <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Редагування туру</h3>
              
              <!-- Відображення повідомлень -->
              <?php
              if (isset($_SESSION['message'])) {
                  echo '<div class="alert alert-info" role="alert">' . $_SESSION['message'] . '</div>';
                  unset($_SESSION['message']);
              }
              ?>
              
              <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <input type="text" id="title" name="title" class="form-control form-control-lg" value="<?= htmlspecialchars($tour['title']) ?>" required />
                      <label class="form-label" for="title">Назва туру</label>
                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <select id="class" name="class" class="form-control form-control-lg" required>
                        <option value="C" <?= $tour['class'] == 'C' ? 'selected' : '' ?>>C</option>
                        <option value="B" <?= $tour['class'] == 'B' ? 'selected' : '' ?>>B</option>
                        <option value="A" <?= $tour['class'] == 'A' ? 'selected' : '' ?>>A</option>
                        <option value="S" <?= $tour['class'] == 'S' ? 'selected' : '' ?>>S</option>
                        <option value="S+" <?= $tour['class'] == 'S+' ? 'selected' : '' ?>>S+</option>
                      </select>
                      <label class="form-label" for="class">Клас</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <input type="text" id="country" name="country" class="form-control form-control-lg" value="<?= htmlspecialchars($tour['country']) ?>" required />
                      <label class="form-label" for="country">Країна</label>
                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <input type="text" id="city" name="city" class="form-control form-control-lg" value="<?= htmlspecialchars($tour['city']) ?>" required />
                      <label class="form-label" for="city">Місто</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <input type="number" id="available_seats" name="available_seats" class="form-control form-control-lg" value="<?= htmlspecialchars($tour['available_seats']) ?>" required />
                      <label class="form-label" for="available_seats">Доступні місця</label>
                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <input type="number" id="price" name="price" class="form-control form-control-lg" value="<?= htmlspecialchars($tour['price']) ?>" required />
                      <label class="form-label" for="price">Ціна</label>
                    </div>
                  </div>
                </div>
                <div class="mb-4">
                  <div data-mdb-input-init class="form-outline">
                    <textarea id="short_description" name="short_description" class="form-control form-control-lg" rows="3" required><?= htmlspecialchars($tour['short_description']) ?></textarea>
                    <label class="form-label" for="short_description">Короткий опис</label>
                  </div>
                </div>
                <div class="mb-4">
                  <div data-mdb-input-init class="form-outline">
                    <textarea id="detailed_description" name="detailed_description" class="form-control form-control-lg" rows="5"><?= htmlspecialchars($tour['detailed_description']) ?></textarea>
                    <label class="form-label" for="detailed_description">Детальний опис (необов'язково)</label>
                  </div>
                </div>
                <div class="mb-4">
                  <div data-mdb-input-init class="form-outline">
                    <input type="file" id="image" name="image" class="form-control form-control-lg" />
                    <label class="form-label" for="image">Зображення</label>
                    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($tour['image']) ?>">
                  </div>
                </div>
                <div class="mb-4">
                  <div data-mdb-input-init class="form-outline">
                    <label class="form-label" for="tags">Теги</label>
                    <select id="tags" name="tags[]" class="form-control form-control-lg" multiple>
                      <?php foreach ($tags as $tag): ?>
                          <option value="<?= $tag['tag_id'] ?>" <?= in_array($tag['tag_id'], $tour_tags) ? 'selected' : '' ?>><?= htmlspecialchars($tag['name']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="mb-4">
                  <a href="add_tag.php" target="_blank" class="btn btn-info">Додати новий тег</a>
                </div>
                <div class="mt-2 pt-2">
                  <button class="btn btn btn-block fa-lg gradient-custom-2 mb-3" style="background-color: #f55105; color: #FFFEFA;" type="submit">Завершити</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
