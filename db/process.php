<?php
// Povezivanje sa MySQL bazom
$host = 'localhost';
$dbname = 'restoran';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Greška pri povezivanju sa bazom: " . $e->getMessage());
}

// Uzimanje podataka iz forme
$burger_id = $_POST['burger'];
$addons = isset($_POST['addons']) ? $_POST['addons'] : [];

// Uzimanje burgera iz baze
$sql = "SELECT * FROM burgers WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $burger_id, PDO::PARAM_INT);
$stmt->execute();
$burger = $stmt->fetch(PDO::FETCH_ASSOC);

// Dodavanje cena za dodatke
$addons_price = 0;
$addon_prices = [
    'cheese' => 1.5,
    'bacon' => 2.0,
    'pickles' => 0.5
];

foreach ($addons as $addon) {
    if (isset($addon_prices[$addon])) {
        $addons_price += $addon_prices[$addon];
    }
}

// Izračunavanje ukupne cene
$total_price = $burger['cena'] + $addons_price;
echo "<h1>Uspešno ste naručili: " . $burger['naziv'] . " za $" . $total_price . ".</h1>";
?>
