<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birthdate = $_POST['birthdate'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("
                INSERT INTO users (email, first_name, last_name, birthdate, password)
                VALUES (:email, :first_name, :last_name, :birthdate, :password)
            ");
            $stmt->execute([
                ':email' => $email,
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':birthdate' => $birthdate,
                ':password' => $hashed_password
            ]);

            $_SESSION['message'] = "Реєстрація успішна!";
            header("Location: PA.php"); // Перенаправлення на сторінку успішної реєстрації
            exit();
        } catch (PDOException $e) {
            $_SESSION['message'] = "Помилка під час реєстрації: " . $e->getMessage();
        }
    } else {
        $_SESSION['message'] = "Паролі не співпадають";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реєстрація</title>
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
              <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Реєстрація облікового запису</h3>
              
              <!-- Відображення повідомлень -->
              <?php
              if (isset($_SESSION['message'])) {
                  echo '<div class="alert alert-info" role="alert">' . $_SESSION['message'] . '</div>';
                  unset($_SESSION['message']);
              }
              ?>
              
              <form action="" method="POST">
                <div class="row">
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <input type="text" id="first_name" name="first_name" class="form-control form-control-lg" required />
                      <label class="form-label" for="first_name">Ім'я</label>
                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <input type="text" id="last_name" name="last_name" class="form-control form-control-lg" required />
                      <label class="form-label" for="last_name">Прізвище</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-4 d-flex align-items-center">
                    <div data-mdb-input-init class="form-outline datepicker w-100">
                      <input type="date" class="form-control form-control-lg" id="birthdate" name="birthdate" required />
                      <label for="birthdate" class="form-label">День народження</label>
                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <input type="email" id="email" name="email" class="form-control form-control-lg" required />
                      <label class="form-label" for="email">Email</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="password" id="form3Example4cg" name="password" class="form-control form-control-lg" required />
                    <label class="form-label" for="form3Example4cg">Пароль</label>
                  </div>
                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="password" id="form3Example4cdg" name="confirm_password" class="form-control form-control-lg" required />
                    <label class="form-label" for="form3Example4cdg">Підтвердіть пароль</label>
                  </div>
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
