<?php // plik ze stroną główną sklepu - możliwość przeglądania i dodawania produktów do koszyka, odnośniki
require_once 'bazaDanych.php'; // wymagany plik
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona główna</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .zarzadzaj {
            display: flex;
            position: absolute;
            top: 10px;
            left: 10px;
            width: 99%;
            justify-content: space-between;
        }

        .ikona_koszyk img {
            display: flex;
            width: 50px;
            height: 50px;
        }
    </style>
</head>
<body>

    <div class="zarzadzaj">
        <a href="stan.php">Zarządzaj produktami</a> <!-- odnośnik do strony z mechaniką zarządzania produktami w magazynie -->

        <div class="koszyk">
            <div class="ikona_koszyk">
                <a href="koszyk.php"><img src="koszyk.png" alt="Koszyk"></a> <!-- kliknięcie w ikonękoszyka odnosi do strony z koszykiem -->
            </div>
        </div>
    </div>

    <h1>Strona główna sklepu</h1>
</body>
</html>
