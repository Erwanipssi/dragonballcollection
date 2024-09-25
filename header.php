<?php
session_start();
require_once('./dbconnect/dbconnect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DBZ Styled Navbar</title>
    <link rel="stylesheet" href="./public/css/header.css">
    <link href="https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap" rel="stylesheet"> <!-- Custom font -->
</head>
<body>
    <header>
        <nav class="dbz-navbar">
            <div class="logo">
                <a href="index.php">
                    <img src="./public/img/cristaleball.png" alt="Logo" class="logo-img"/>
                </a>
            </div>
            <ul class="nav-links">
                <li><a href="shop.php"><img src="./public/img/baba.png" alt="Shop" class="nav-icon"/><span>Shop</span></a></li>
                <li><a href="galerie.php"><img src="./public/img/detecteur.png" alt="Gallery" class="nav-icon"/><span>Galerie</span></a></li>
                <li><a href="aventure.php"><img src="./public/img/piccolo.png" alt="Gallery" class="nav-icon"/><span>Aventure</span></a></li>
                <li><a href="classement.php"><img src="./public/img/mrsatan.png" alt="Gallery" class="nav-icon"/><span>Classement</span></a></li>
                <li><a href="./deconnect/logout.php"><img src="./public/img/nuage.png" alt="Logout" class="nav-icon"/><span>Logout</span></a></li>
            </ul>
        </nav>
    </header>
</body>
</html>

