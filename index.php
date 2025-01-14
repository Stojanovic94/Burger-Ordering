<?php
// Uključivanje konekcije sa bazom
include 'db/db.php';
// Uzimanje svih burgera iz baze
$query = $pdo->query("SELECT * FROM burgers");
$burgers = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burger House</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/order.js" defer></script> <!-- Linkovanje JS fajla -->
</head>
<body>
    <h1>Naruči svoj burger</h1>
    <!-- Forma za naručivanje burgera -->
    <form id="burgerForm" action="process.php" method="POST">
        <label for="customer_name">Ime kupca:</label>
        <input type="text" id="customer_name" name="customer_name" required>

        <label for="phone_number">Broj telefona:</label>
        <input type="text" id="phone_number" name="phone_number" required>

        <label for="address">Adresa:</label>
        <input type="text" id="address" name="address" placeholder="Obavezno za dostavu">

        <label for="burger">Izaberi burger:</label>
        <select name="burger" id="burger">
            <?php foreach ($burgers as $burger): ?>
                <option value="<?= $burger['id'] ?>"><?= $burger['naziv'] ?> - $<?= $burger['cena'] ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="quantity">Količina:</label>
        <input type="number" id="quantity" name="quantity" min="1" value="1"><br><br>

        <!-- Box za metod dostave -->
        <div class="delivery-method-box">
            <label for="delivery">Metod dostave:</label><br>
            <input type="radio" id="pickup" name="delivery" value="pickup">
            <label for="pickup">Preuzimanje</label><br>
            <input type="radio" id="delivery" name="delivery" value="delivery" checked>
            <label for="delivery">Dostava</label><br><br>
        </div>

        <!-- Box za dodatke -->
        <div class="addons-box">
            <label for="addons">Dodatak:</label><br>
            <input type="checkbox" id="cheese" name="addons[]" value="cheese">
            <label for="cheese">Topljeni sir</label><br>
            <input type="checkbox" id="bacon" name="addons[]" value="bacon">
            <label for="bacon">Slanina</label><br>
            <input type="checkbox" id="pickles" name="addons[]" value="pickles">
            <label for="pickles">Kiseli krastavci</label><br><br>
        </div>

        <button type="submit" id="calculateBtn">Izračunaj cenu</button>
    </form>

    <!-- Div gde će se prikazati rezultat -->
    <div id="loading" style="display: none;">Učitavanje...</div>
    <div id="result" class="result" style="display: none;"></div>

    <script>
        // Dodavanje event listener-a za dugme
        document.getElementById('calculateBtn').addEventListener('click', function (event) {
            event.preventDefault(); // Sprečavanje slanja forme

            // Validacija unosa
            const phoneNumber = document.getElementById('phone_number').value;
            const phoneRegex = /^[0-9]{10}$/; // Provera da li je broj telefona validan (10 cifara)
            if (!phoneRegex.test(phoneNumber)) {
                alert('Unesite validan broj telefona.');
                return;
            }

            const address = document.getElementById('address').value;
            if (document.querySelector('input[name="delivery"]:checked').value === 'delivery' && !address) {
                alert('Adresa je obavezna za dostavu.');
                return;
            }

            // Prikupljanje podataka iz forme
            const formData = new FormData(document.getElementById('burgerForm'));

            // Dodavanje dodataka (ako postoje)
            const addons = [];
            document.querySelectorAll('input[name="addons[]"]:checked').forEach(function (checkbox) {
                addons.push(checkbox.value);
            });
            formData.append('addons', addons);

            // Prikazivanje indikatora učitavanja
            document.getElementById('loading').style.display = 'block';

            // Slanje AJAX zahteva
            fetch('DataBase/process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Prikazivanje rezultata
                const resultDiv = document.getElementById('result');
                resultDiv.innerHTML = data;
                resultDiv.style.display = 'block';

                // Sakrivanje indikatora učitavanja
                document.getElementById('loading').style.display = 'none';
            })
            .catch(error => {
                console.error('Greška:', error);
                alert('Došlo je do greške pri obradi narudžbine.');
                document.getElementById('loading').style.display = 'none';
            });
        });
    </script>
</body>
</html>
