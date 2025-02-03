<!-- możliwość dodawania i usuwania produktów w bazie sklepu -->
<?php
require_once 'bazaDanych.php'; // wymagany plik
$sciezka = "ikony/"; // ścieżka do katalogu na zdjęcia

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['dodaj'])) {
        $nazwa = $_POST['nazwa'];
        $opis = $_POST['opis'];
        $stan = intval($_POST['stan']);
        $cena = floatval($_POST['cena']);

        if (!empty($_FILES["zdjecie"]["name"])) { // przesłanie zdjęcia produktu
            $plik = $sciezka . basename($_FILES["zdjecie"]["name"]);

            if (move_uploaded_file($_FILES["zdjecie"]["tmp_name"], $plik))
                $zdjecie = $plik;
            else
                die("Błąd przesyłania pliku.");}
        else
            $zdjecie = "";

        $p = $db->p->prepare("INSERT INTO produkty (nazwa, opis, zdjecie, stan, cena) VALUES (?, ?, ?, ?, ?)"); // dodanie produktu do bazy
        $p->bindValue(1, $nazwa, SQLITE3_TEXT);
        $p->bindValue(2, $opis, SQLITE3_TEXT);
        $p->bindValue(3, $zdjecie, SQLITE3_TEXT);
        $p->bindValue(4, $stan, SQLITE3_INTEGER);
        $p->bindValue(5, $cena, SQLITE3_FLOAT);
        $p->execute();
    }

    if (isset($_POST['usun'])) { // usunięcie produktu z bazy
        $id = intval($_POST['id']);
        $p = $db->p->prepare("DELETE FROM produkty WHERE id = ?");
        $p->bindValue(1, $id, SQLITE3_INTEGER);
        $p->execute();
    }
}
$produkty = $db->p->query("SELECT * FROM produkty"); // pobranie listy produktów
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie stanem</title>
    <style>
        body {
            display: flex;
            align-items: center;
            flex-direction: column;
            font-family: Arial, sans-serif;
            margin-bottom: 100px;
        }

        .dom {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .dom img {
            width: 40px;
            height: 40px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input, textarea {
            margin-bottom: 10px;
            padding: 10px;
        }

        button {
            padding: 10px;
            background-color:rgb(33, 90, 10);
            color: white;
            border: none;
            cursor: pointer;
        }

        table {
            width: 70%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: rgba(33, 90, 10, 0.50);
        }

        .wyglad_form {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0 50px 0;
            width: 400px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="dom"> 
        <a href="glowna.php"> <!-- odnośnik do strony głównej -->
            <img src="ikony/dom.png" alt="Strona główna">
        </a>
    </div>
    <h1>Zarządzanie produktami</h1>

    <form method="POST" enctype="multipart/form-data" class="wyglad_form">  <!-- formularz - dodawanie produktów -->
        <input type="text" name="nazwa" placeholder="Nazwa produktu" required>
        <textarea name="opis" placeholder="Opis produktu"></textarea>
        <input type="number" name="stan" placeholder="Ilość w magazynie" required>
        <input type="file" name="zdjecie" accept=".jpg, .jpeg, .png">
        <input type="number" step="0.01" name="cena" placeholder="Cena (zł)" required>
        <button type="submit" name="dodaj">Dodaj produkt</button>
    </form>

    <h2>Lista produktów</h2>
    <table> <!-- tworzymy tabelę z dostępnymi produktami -->
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
            <th>Opis</th>
            <th>Zdjęcie</th>
            <th>Stan</th>
            <th>Cena</th>
            <th>Usuń</th>
        </tr>
        <?php while ($produkt = $produkty->fetchArray(SQLITE3_ASSOC)): ?> <!-- pobieramy produkty z bazy -->
        <tr>
            <td><?= $produkt['id'] ?></td>
            <td><?= $produkt['nazwa'] ?></td>
            <td><?= $produkt['opis'] ?></td>
            <td>
                <?php if (!empty($produkt['zdjecie'])): ?>
                    <img src="<?= $produkt['zdjecie'] ?>" width="75">
                <?php else: ?>
                    Brak
                <?php endif; ?>
            </td>
            <td><?= $produkt['stan'] ?></td>
            <td><?= number_format($produkt['cena'], 2) ?> zł</td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $produkt['id'] // pobranie id usuwanego produktu ?>">
                    <button type="submit" name="usun" style="background: none;">
                        <img src="ikony/usun.png" alt="Usuń" width="40" height="40">
                    </button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>