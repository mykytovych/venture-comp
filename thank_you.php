<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Дякуємо за замовлення</title>
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
    <h1 style="font-size: 92px;"><b>Дякуємо за ваше замовлення!</b></h1>
</div>
<div class="container">
    <p>Ваше замовлення успішно оформлено. Дякуємо, що обрали нашу компанію!</p>
    <a href="tours.php" class="btn custom-button" style="background-color: #f55105; color: #FFFEFA;">Повернутися до турів</a>
</div>
<footer class="text-white text-center" style="padding: 20px 0; background-color: #f55105; position: absolute; bottom: 0; width: 100%;">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>
