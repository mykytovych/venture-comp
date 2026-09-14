<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['birthdate'] = $user['birthdate'];
        $_SESSION['profile_photo'] = $user['profile_photo'];
        $_SESSION['is_admin'] = $user['is_admin'];
        header("Location: PA.php");
        exit();
    } else {
        $_SESSION['message'] = "Невірний email або пароль";
        header("Location: login.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід</title>
    <link rel="icon" type="image/x-icon" href="travells-favicon-color (1).png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <section class="h-100 gradient-form" style="background-color: #eee;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10">
          <div class="card rounded-3 text-black">
            <div class="row g-0">
              <div class="col-lg-6">
                <div class="card-body p-md-5 mx-md-4">
                  <div class="text-center">
                    <img src="travells-high-resolution-logo.png" style="width: 185px;" alt="logo">
                    <h4 class="mt-1 mb-5 pb-1">Подорожуйте з нами!</h4>
                  </div>
                  <form action="" method="POST">
                    <p>Ввійдіть в свій аккаунт</p>
                    <div class="form-outline mb-4">
                      <input type="email" id="email" name="email" class="form-control" placeholder="Логін" required />
                      <label class="form-label" for="email">Email</label>
                    </div>
                    <div class="form-outline mb-4">
                      <input type="password" id="password" name="password" class="form-control" required />
                      <label class="form-label" for="password">Пароль</label>
                    </div>
                    <div class="text-center pt-1 mb-5 pb-1">
                      <button class="btn btn btn-block fa-lg gradient-custom-2 mb-3" style="background-color: #fa4700; color: #FFFEFA;" type="submit">Вхід</button>
                      <a class="text-muted" href="#!">Забули пароль?</a>
                    </div>
                    <div class="d-flex align-items-center justify-content-center pb-4">
                      <p class="mb-0 me-2">Вперше тут?</p>
                      <button type="button" class="btn btn-outline-danger" onclick="window.location.href = 'signup.php'">Створити аккаунт</button>
                    </div>
                  </form>

                  <?php
                  if (isset($_SESSION['message'])) {
                      echo '<div class="alert alert-info" role="alert">' . $_SESSION['message'] . '</div>';
                      unset($_SESSION['message']);
                  }
                  ?>
                </div>
              </div>
              <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                <div class="text-dark px-3 py-4 p-md-5 mx-md-4">
                  <h4 class="mb-4">Дякуємо за довіру!</h4>
                  <p class="small mb-0">Ми дбаємо про конфіденційних даних наших клієнтів.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
