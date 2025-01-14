<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $burger_id = $_POST['burger'];

    $sql = "SELECT * FROM burgers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $burger_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $burger = $result->fetch_assoc();
        echo "<h1>Uspešno ste naručili: " . $burger['naziv'] . " za " . $burger['cena'] . " €.</h1>";
    } else {
        echo "<h1>Greška u naručivanju!</h1>";
    }

    $stmt->close();
}

$conn->close();
?>
