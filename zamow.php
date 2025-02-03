<!-- realizacja zamówienia, wybór metody wysyłki -->
<?php
    require_once 'bazaDanych.php';
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
        }

        img {
            width: 40px;
            height: 40px;
        }

        .koszyk {
            display: flex;
            align-items: center;
        }

    </style>
</head>
<body>

    <div class="gora">
        <a href="glowna.php"> <!-- odnośnik na stronę główną -->
            <img src="ikony/dom.png" alt="Strona główna" class="dom">
        </a>
        <div class="koszyk"> <!-- odnośnik do koszyka -->
            <a href="koszyk.php">
                <img src="ikony/koszyk.png" alt="Koszyk">
            </a>
        </div>
    </div>
    </div>

    <h1>Realizacja zamówienia</h1>
        
</body>
</html>