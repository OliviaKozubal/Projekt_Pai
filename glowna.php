<!-- plik ze stroną główną sklepu - możliwość przeglądania i dodawania produktów do koszyka, odnośniki -->
<?php 
    session_start();
    require_once 'bazaDanych.php'; // wymagany plik
    $query = $db->p->query("SELECT * FROM produkty");
    $produkty = [];
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) { // pobranie produktów z bazy
        $produkty[] = $row; 
}

    
    if (isset($_POST['dodaj'])) { // dodanie produktu do koszyka
        $id_produkt = intval($_POST['id_produkt']);
        $ilosc = intval($_POST['ilosc']);

    if (!isset($_SESSION['koszyk'])) { // czy koszyk już istnieje
        $_SESSION['koszyk'] = [];
    }

    if (isset($_SESSION['koszyk'][$id_produkt])) { // dodaj produkt lub zwiększ ilość
        $_SESSION['koszyk'][$id_produkt] += $ilosc;
    } else {
        $_SESSION['koszyk'][$id_produkt] = $ilosc;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona Główna</title>
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
        }

        img {
            width: 40px;
            height: 40px;
        }

        .koszyk {
            display: flex;
            align-items: center;
        }

        h1 {
            display: inline-block;
            margin-left: 200px;
        }

        .produkty_kontener {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            margin-top: 50px;
            padding: 25px;
            border: 10px solid rgba(33, 90, 10, 0.60);
            border-radius: 10px;
        }

        .produkt {
            display: flex;
            border: 2px solid rgba(33, 90, 10, 0.50);
            border-radius: 10px;
            padding: 15px;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
        
        .produkt button {
            background: none; 
            border: none;
        }
        
        .produkt img {
            width: 160px;
            height: auto;
        }

        .produkt h3 {
            margin: 10px 0;
        }

        .cena {
            font-weight: bold;
        }

        .ilosc {
            font-size: 14px;
            color: #555;
        }

        .dodaj img {
            width: 30px;
            height: 30px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="gora">
        <a href="stan.php"> <!-- odnośnik do zarządzania stanem sklepu -->
            <img src="ikony/ustawienia.png" alt="Zarządzaj stanem" class="zebatka"> 
        </a>

        <h1>Strona Główna</h1>

        <div class="koszyk"> <!-- odnośnik do koszyka -->
            <a href="koszyk.php">
                <img src="ikony/koszyk.png" alt="Koszyk">
            </a>
            <span>Ilość produktów w koszyku: <?= isset($_SESSION['koszyk']) ? array_sum($_SESSION['koszyk']) : 0 ?></span> <!-- aktualizowana ilość przedmiotów w koszyku -->
        </div>
    </div>

    <div class="produkty_kontener"> <!-- wyświetlanie produktów -->
        <?php foreach ($produkty as $produkt): ?>
            <div class="produkt">
                <div>
                    <h3><?= $produkt['nazwa'] ?></h3>
                    <p><?= $produkt['opis'] ?></p>
                    <p class="cena"><?= number_format($produkt['cena'], 2) ?> zł</p>
                    <p class="ilosc"><strong>Ilość w magazynie: </strong><?= $produkt['stan'] ?></p>
                    <form method="POST" action="">
                        <input type="hidden" name="id_produkt" value="<?= $produkt['id'] ?>">
                        <input type="hidden" name="ilosc" value="1"> <!-- Dodawanie jednej sztuki na raz -->
                        <button type="submit" name="dodaj" class="dodaj"><img src="ikony/dodaj.png" alt="dodaj"></button>
                    </form>
                </div>
                <img src="<?= $produkt['zdjecie'] ?>" alt="<?= $produkt['nazwa'] ?>">
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>