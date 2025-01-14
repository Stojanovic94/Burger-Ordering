document.getElementById('calculateBtn').addEventListener('click', function (event) {
    event.preventDefault(); // Sprečavanje slanja forme

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
    })
    .catch(error => {
        console.error('Greška:', error);
        alert('Došlo je do greške pri obradi narudžbine.');
    });
});
