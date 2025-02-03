<!-- realizacja zamówienia, wybór metody wysyłki -->
<?php
require_once 'bazaDanych.php';
session_start();

$kwota_produkty = $_SESSION['kwota_produkty'] ?? 0;

$wysylka = [];
$t = $db->p->query("SELECT * FROM wysylka");
while ($row = $t->fetchArray(SQLITE3_ASSOC)) {
    $wysylka[] = $row;
}

$koszt_wysylka =$wysylka[0]['koszt']; // domyślny koszt wysyłki
$koszt = $kwota_produkty + $koszt_wysylka;

if ($_SERVER["REQUEST_METHOD"] === "POST") { // zmniejszanie ilości przedmiotów w bazie po zakupie
    foreach ($_SESSION['koszyk'] as $id_produktu => $ilosc) {
        $u = $db->p->prepare("SELECT stan FROM produkty WHERE id = ?");
        $u->bindValue(1, $id_produktu, SQLITE3_INTEGER);
        $w = $u->execute()->fetchArray(SQLITE3_ASSOC);

        if ($w) {
            $nowyStan = max(0, $wynik['stan'] - $ilosc);

            $update = $db->p->prepare("UPDATE produkty SET stan = ? WHERE id = ?");
            $update->bindValue(1, $nowyStan, SQLITE3_INTEGER);
            $update->bindValue(2, $id_produktu, SQLITE3_INTEGER);
            $update->execute();
        }
    }

    $_SESSION['koszyk'] = []; // po zakupie czyścimy koszyk oraz kwotę, przeniesienie na stronęz potwierdzeniem
    $_SESSION['kwota_produkty'] = 0;

    header("Location: potwierdzenie.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizacja zamówienia</title>
    <style>
        body {
            display: flex;
            align-items: center;
            flex-direction: column;
            font-family: Arial, sans-serif;
        }

        .gora {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color:rgba(33, 90, 10, 0.25);
        }

        .dom, .koszyk {
            display: flex;
            align-items: center;
        }

        img {
            width: 40px;
            height: 40px;
        }

        h1 {
            text-align: center;
        }

        .zamow {
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            margin-top: 30px;
            padding: 15px;
            text-align: center;
        }

        button {
            padding: 10px;
            width: 100px;
            background-color: rgb(33, 90, 10);
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="gora">
        <div class="dom">
            <a href="glowna.php">
                <img src="ikony/dom.png" alt="Strona główna"> <!-- odnośnik do strony głównej -->
            </a>
        </div>

        <h1>Realizacja zamówienia</h1>

        <div class="koszyk">
            <a href="koszyk.php"><img src="ikony/koszyk.png" alt="Koszyk"></a> <!-- odnośnik do koszyka -->
        </div>
    </div>
    
    <div class="zamow">
        <h3>Całkowity koszt produktów: <span id="kwota_produkty"><?= number_format($kwota_produkty, 2) ?></span> zł</h3>

        <form method="POST"> <!-- wybór metody wysyłki -->
            <label for="wysylka">Wybierz metodę wysyłki:</label>
            <select name="wybrana_wysylka" id="wybrana_wysylka" required>
                <?php foreach ($wysylka as $opcja): ?>
                    <option value="<?= $opcja['id'] ?>" k="<?= $opcja['koszt'] ?>">
                        <?= $opcja['nazwa'] ?> - <?= number_format($opcja['koszt'], 2) ?> zł
                    </option>
                <?php endforeach; ?>
            </select>

            <h3>Całkowity koszt: <span id="calkowity_koszt"><?= number_format($koszt, 2) ?></span> zł</h3>
            <button type="submit">Kup</button>
        </form>
    </div>

    <script> // funkcja licząca nowy koszt całkowity przy zmianie sposobu wysyłki
        document.getElementById('wybrana_wysylka').addEventListener('change', function () {
            let koszt_wysylki = parseFloat(this.options[this.selectedIndex].getAttribute('k'));
            let koszt_produktow = parseFloat(document.getElementById('kwota_produkty').innerText.replace(',', '.'));
            let koszt = koszt_produktow + koszt_wysylki;
            document.getElementById('calkowity_koszt').innerText = koszt.toFixed(2).replace('.', ',');
        });
    </script>
</body>
</html>