<?php // strona z koszykiem - odnośnik do realizacji zamówienia, usuwanie produktów z koszyka, zmienianie ilości
    require_once 'bazaDanych.php'; // wymagany plik
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
        }
    </style>
</head>
<body>

    <h1>Koszyk</h1>

    <div>
        <a href="zamow.php">Realizuj zamówienie</a> <!-- odnośnik do strony gdzie można zrealizować zamówienie -->
    </div>
</body>
</html>