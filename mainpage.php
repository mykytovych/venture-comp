<?php
session_start();
require 'db_connect.php';

$stmt = $pdo->query("SELECT * FROM tours ORDER BY RAND() LIMIT 3");
$hot_tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venture Comp. турагент</title>
    <link rel="icon" type="image/x-icon" href="travells-favicon-color (1).png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        .card {
            background-color: #f55105;
            color: white;
        }
        .card h5, .card p {
            color: white;
        }
        .card-img-top {
            max-height: 200px;
            object-fit: cover;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card {
            height: 100%;
        }
        .card-text {
            flex-grow: 1;
        }
        .card-title {
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
<header  style="background-color: #f55105; position: fixed; z-index: 100;  width: 100%;">
  <nav class="navbar navbar-expand-lg oswald-font">
      <div class="container-fluid">
        <a class="navbar-brand" href="#"  style="color: #FFFEFA;"><img src="travells-favicon-color.png" alt="Турагенство" style="width: 50px; height: 50px;"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link disabled" href="#"  style="color: #fffefa92;">Головна</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="tours.php"  style="color: #FFFEFA;">Тури</a>
            </li>
            <li class="nav-item">
              <a class="nav-link"  href="PA.php">
                <img src="person.svg" alt="Особистий кабінет">
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
</header>

<div id="carouselSearchContainer" style="position: relative; text-align: center;">
    <div class="search-container" style="position: absolute; top: 270px; left: 51%; transform: translateX(-50%); z-index: 2;">
        <form action="tours.php" method="GET">
            <input type="search" name="search" class="form-control rounded-pill" style="width: 650px; height: 40px;" placeholder="Знайдіть ідеальний тур для себе" aria-label="Search" aria-describedby="search-addon" />
            <button type="submit" class="btn btn-bd-or rounded-pill" style="background-color: #f55105; font-family: 'Oswald', sans-serif; margin-top: 1%;">START</button>
        </form>
    </div>
</div>

  
  <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="5" aria-label="Slide 6"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="6" aria-label="Slide 7"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="7" aria-label="Slide 8"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="8" aria-label="Slide 9"></button>
      </div>
      <div class="carousel-inner blurred-image">
        <div class="carousel-item active">
          <img src="image (1).png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="image (3).png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="image (5).png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="image (6).png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="image (7).png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="image (9).png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="image (11).png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="image (15).png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="image (17).png" class="d-block w-100" alt="...">
        </div>
      </div>
      
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
      </button>
  </div>
  
  <div class="col-md-6 offset-md-3 text-center" style="margin-top: 60px; margin-bottom: 10px;">
    <h1 style="font-size: 92px;"><b>ГАРЯЧІ ТУРИ</b></h1>
  </div>

  <div class="container" style="margin-top: 100px;">
  <div class="row mt-4">
        <div class="col-12">
            <div class="row">
                <?php foreach ($hot_tours as $tour) : ?>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                            <img src="<?= htmlspecialchars($tour['image']) ?>" alt="<?= htmlspecialchars($tour['title']) ?>" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($tour['title']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($tour['short_description']) ?></p>
                                <p class="card-text"><strong>Ціна: <?= htmlspecialchars($tour['price']) ?> грн</strong></p>
                                <a href="tour_detail.php?tour_id=<?= htmlspecialchars($tour['tour_id']) ?>" class="custom-white-button">Детальніше</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
  </div>

  <div class="container-fluid vh-100 d-flex flex-column justify-content-center align-items-center" style="margin-top: 2.5%;">
    <div class="text-center">
      <div class="embed-responsive" >
        <iframe style="width: 1200px; height: 630px;" class="embed-responsive-item" src="https://www.youtube.com/embed/k34sY-npVg0" allowfullscreen></iframe>
      </div>
      <p style="font-size: 18px;">Відкрийте світ разом з Venture Comp. Ділимося пригодами, які залишають слід в серці. Миттєва відправка до країн мрій. Експерти в організації незабутніх подорожей.</p>
    </div>
  </div>
  
    <footer  class="text-white text-center" style="padding: 20px 0; background-color: #f55105;">
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

</body>
</html>
