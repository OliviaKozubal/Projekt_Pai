<?php // strona z koszykiem - odnośnik do realizacji zamówienia, usuwanie produktów z koszyka, zmienianie ilości
    require_once 'bazaDanych.php'; // wymagany plik
    session_start();

    if (isset($_POST['usun'])) { // usuń produkt z koszyka
        $id_produkt = intval($_POST['id_produkt']);
        unset($_SESSION['koszyk'][$id_produkt]);
    }

    if (isset($_POST['zmien_ilosc'])) { // zmień ilość produktu
        $id_produkt = intval($_POST['id_produkt']);
        $ilosc = intval($_POST['ilosc']);
        $_SESSION['koszyk'][$id_produkt] = $ilosc;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koszyk</title>
    <style>
        body {
            display: flex;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            font-family: Arial, sans-serif;
        }

        .dom {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        img {
            width: 40px;
            height: 40px;
        }

        table {
            width: 40%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: rgba(33, 90, 10, 0.50);
        }

        .realizuj {
            padding: 10px;
            background-color:rgb(33, 90, 10);
            border: none;
            cursor: pointer;
            width: 200px;
        }
        
        .realizuj a {
            color: white;
            text-decoration: none;
        }

        .usun button {
            border: none;
            background: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="dom">
        <a href="glowna.php">
            <img src="ikony/dom.png" alt="Strona główna"> <!-- odnośnik do strony głównej -->
        </a>
    </div>

    <h1>Koszyk</h1>
    <?php if (!empty($_SESSION['koszyk'])): // jeżeli koszyk nie jest pusty ?> 
        <table>
            <tr>
                <th>Nazwa</th>
                <th>Cena</th>
                <th>Ilość</th>
                <th>Usuń</th>
            </tr>
            <?php
            $sumaCen = 0;
            foreach ($_SESSION['koszyk'] as $id_produkt => $ilosc) { // sumowanie cen produktów
                $p = $db->p->prepare("SELECT * FROM produkty WHERE id = :id");
                $p->bindValue(':id', $id_produkt, SQLITE3_INTEGER);
                $produkt = $p->execute()->fetchArray(SQLITE3_ASSOC);
                $sumaCen += $produkt['cena'] * $ilosc;
            ?>
                <tr>
                    <td><?= $produkt['nazwa'] ?></td>
                    <td><?= $produkt['cena'] ?> zł</td>
                    <td>
                        <form method="POST"> <!-- zmiana ilości produktu -->
                            <input type="hidden" name="id_produkt" value="<?= $produkt['id'] ?>">
                            <input type="number" name="ilosc" value="<?= $ilosc ?>" min="1" onchange="this.form.submit()" style="width: 50px">
                            <input type="hidden" name="zmien_ilosc" value="1">
                        </form>
                    </td>
                    <td>
                        <form method="POST" class="usun"> <!-- usuwanie produktu -->
                            <input type="hidden" name="id_produkt" value="<?= $produkt['id'] ?>">
                            <button type="submit" name="usun"><img src="ikony/usun.png" alt="usun"></button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <h3>Cena produktów: <?= number_format($sumaCen, 2) ?> zł</h3>
    <?php else: ?>
        <p>Pusto? Trzeba coś tu dodać!</p> <!-- jeżeli koszyk jest pusty -->
    <?php endif; ?>

        <button type="button" class="realizuj"><a href="zamow.php">Realizuj zamówienie</a></button> <!-- odnośnik do strony gdzie można zrealizować zamówienie -->
</body>
</html>