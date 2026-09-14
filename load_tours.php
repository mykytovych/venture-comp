<?php
require 'db_connect.php';

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 3;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$class = isset($_GET['class']) ? $_GET['class'] : '';
$country = isset($_GET['country']) ? $_GET['country'] : '';
$city = isset($_GET['city']) ? $_GET['city'] : '';
$price_min = isset($_GET['price_min']) ? (int)$_GET['price_min'] : 0;
$price_max = isset($_GET['price_max']) ? (int)$_GET['price_max'] : PHP_INT_MAX;
$hide_no_seats = isset($_GET['hide_no_seats']) ? (bool)$_GET['hide_no_seats'] : false;

try {
    $query = "
        SELECT tours.*, GROUP_CONCAT(tags.name SEPARATOR ', ') as tags, GROUP_CONCAT(tags.icon_url SEPARATOR ', ') as tag_icons
        FROM tours
        LEFT JOIN tour_tags ON tours.tour_id = tour_tags.tour_id
        LEFT JOIN tags ON tour_tags.tag_id = tags.tag_id
        WHERE 1=1
    ";

    if (!empty($search)) {
        $query .= " AND tours.title LIKE :search";
    }
    if (!empty($class)) {
        $query .= " AND tours.class = :class";
    }
    if (!empty($country)) {
        $query .= " AND tours.country LIKE :country";
    }
    if (!empty($city)) {
        $query .= " AND tours.city LIKE :city";
    }
    if (!empty($price_min)) {
        $query .= " AND tours.price >= :price_min";
    }
    if (!empty($price_max)) {
        $query .= " AND tours.price <= :price_max";
    }
    if ($hide_no_seats) {
        $query .= " AND tours.available_seats > 0";
    }

    $query .= " GROUP BY tours.tour_id LIMIT :offset, :limit";

    $stmt = $pdo->prepare($query);

    if (!empty($search)) {
        $stmt->bindValue(':search', "%$search%");
    }
    if (!empty($class)) {
        $stmt->bindValue(':class', $class);
    }
    if (!empty($country)) {
        $stmt->bindValue(':country', "%$country%");
    }
    if (!empty($city)) {
        $stmt->bindValue(':city', "%$city%");
    }
    if (!empty($price_min)) {
        $stmt->bindValue(':price_min', $price_min, PDO::PARAM_INT);
    }
    if (!empty($price_max)) {
        $stmt->bindValue(':price_max', $price_max, PDO::PARAM_INT);
    }

    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

    $stmt->execute();
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($tours as $tour) {
        echo '
        <div class="card border border-secondary mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <img src="' . htmlspecialchars($tour['image']) . '" alt="' . htmlspecialchars($tour['title']) . '" class="img-fluid tour-image">
                    </div>
                    <div class="col-md-6">
                        <h3 class="card-text">' . htmlspecialchars($tour['title']) . '</h3>
                        <p>Місце: ' . htmlspecialchars($tour['country']) . ', ' . htmlspecialchars($tour['city']) . '<br>Клас: ' . htmlspecialchars($tour['class']) . '<br>Кількість місць: ' . htmlspecialchars($tour['available_seats']) . ' 
                        <br>Ціна: ' . htmlspecialchars($tour['price']) . ' грн<br>Опис: ' . htmlspecialchars($tour['short_description']) . '</p>
                    </div>
                </div>
                <div>';

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
            </div>
            <a href="tour_detail.php?tour_id=' . htmlspecialchars($tour['tour_id']) . '" class="btn custom-button">Деталі</a>
        </div>';
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
