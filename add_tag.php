<?php
session_start();
require 'db_connect.php';

// Перевірити, чи користувач є адміністратором
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: PA.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $icon_url = $_POST['icon_url'];

    try {
        $stmt = $pdo->prepare("
            INSERT INTO tags (name, icon_url)
            VALUES (:name, :icon_url)
        ");
        $stmt->execute([
            ':name' => $name,
            ':icon_url' => $icon_url
        ]);

        $_SESSION['message'] = "Тег успішно додано!";
        header("Location: add_tag.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['message'] = "Помилка під час додавання тегу: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати тег</title>
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
              <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Додати новий тег</h3>
              
              <!-- Відображення повідомлень -->
              <?php
              if (isset($_SESSION['message'])) {
                  echo '<div class="alert alert-info" role="alert">' . $_SESSION['message'] . '</div>';
                  unset($_SESSION['message']);
              }
              ?>
              
              <form action="" method="POST">
                <div class="mb-4">
                  <div data-mdb-input-init class="form-outline">
                    <input type="text" id="name" name="name" class="form-control form-control-lg" required />
                    <label class="form-label" for="name">Назва тегу</label>
                  </div>
                </div>
                <div class="mb-4">
                  <div data-mdb-input-init class="form-outline">
                    <input type="url" id="icon_url" name="icon_url" class="form-control form-control-lg" required />
                    <label class="form-label" for="icon_url">URL іконки</label>
                  </div>
                </div>
                <div class="mt-2 pt-2">
                  <button class="btn btn btn-block fa-lg gradient-custom-2 mb-3" style="background-color: #FF9B50; color: #FFFEFA;" type="submit">Додати тег</button>
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
