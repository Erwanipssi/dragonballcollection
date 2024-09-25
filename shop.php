<?php

require_once('./header.php'); 

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "Veuillez vous connecter.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer le montant d'or de l'utilisateur
$sql = "SELECT gold FROM user WHERE id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Définir les coûts des personnages par race
$characterCosts = [
    'Human' => 50,
    'Android' => 65,
    'Frieza Race' => 130,
    'Namekian' => 5000,
    'Saiyan' => 300,
    'Angel' => 600,
    'God' => 550,
    'Evil' => 580,
    'Nucleico Benigno' => 520,
    'Nucleico' => 500,
    'Jiren Race' => 540,
    'Majin' => 500,
    'Piccolo' => 1000000,
    'Zeno' => 10000000000,
    'Bills' => 100000,
    'Gogeta' => 2000000,
    'Vegetto' => 2000000
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['character'])) {
    $character = json_decode($_POST['character'], true);
    $characterName = $character['name'];
    $characterRace = $character['race'];

    $cost = isset($characterCosts[$characterRace]) ? $characterCosts[$characterRace] : 90;
    if ($characterName === 'Piccolo') {
        $cost = 1000000;
    } elseif ($characterName === 'Zeno') {
        $cost = 10000000000;
    } elseif ($characterName === 'Bills') {
        $cost = 100000;
    } elseif ($characterName === 'Gogeta' || $characterName === 'Vegetto') {
        $cost = 2000000;
    }

    if ($user['gold'] >= $cost) {
        $insertSql = "INSERT INTO user_characters (user_id, character_name, character_race, character_gender, character_description, character_image) 
                      VALUES (:user_id, :name, :race, :gender, :description, :image)";
        $stmt = $conn->prepare($insertSql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':name' => $character['name'],
            ':race' => $character['race'],
            ':gender' => $character['gender'],
            ':description' => $character['description'],
            ':image' => $character['image']
        ]);

        
        $updateGoldSql = "UPDATE user SET gold = gold - :cost WHERE id = :user_id";
        $stmt = $conn->prepare($updateGoldSql);
        $stmt->bindParam(':cost', $cost, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

    
        header("Location: shop.php");
        exit();
    } else {
        echo "<p>Vous n'avez pas assez de golds pour acheter ce personnage.</p>";
    }
}


$characters = [];
$currentPage = 1;
$totalPages = 1;

do {
    $api_url = "https://dragonball-api.com/api/characters?page=$currentPage";
    $characters_json = file_get_contents($api_url);
    $data = json_decode($characters_json, true);
    if (isset($data['items'])) {
        $characters = array_merge($characters, $data['items']);
        $totalPages = $data['meta']['totalPages'] ?? 1;
    }
    $currentPage++;
} while ($currentPage <= $totalPages);


$search = isset($_POST['search']) ? $_POST['search'] : '';
$selectedRace = isset($_POST['select_race']) ? $_POST['select_race'] : '';


if ($search) {
    $characters = array_filter($characters, function ($character) use ($search) {
        return stripos($character['name'], $search) !== false;
    });
}

if ($selectedRace) {
    $characters = array_filter($characters, function ($character) use ($selectedRace) {
        return $character['race'] === $selectedRace;
    });
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="./public/css/shop.css">
</head>
<body>

<h1>Achetez vos Personnages !!!</h1>

<div class="gold-display">
    <img src="./public/img/senzu.png" alt="" />
    <span><?php echo $user['gold']; ?> senzu</span>
</div>



<form class="search-form" action="" method="post">
    <!-- Barre de recherche stylisée -->
    <input type="text" class="styled-input" name="search" placeholder="Rechercher un personnage...">
    
    <!-- Sélecteur stylisé -->
    <select class="styled-select" name="select_race">
        <option value="">Toutes les races</option>
        <option value="Human">Human</option>
        <option value="Android">Android</option>
        <option value="Frieza Race">Frieza Race</option>
        <option value="Namekian">Namekian</option>
        <option value="Saiyan">Saiyan</option>
        <option value="Angel">Angel</option>
        <option value="God">God</option>
        <option value="Evil">Evil</option>
        <option value="Nucleico Benigno">Nucleico Benigno</option>
        <option value="Nucleico">Nucleico</option>
        <option value="Jiren Race">Jiren Race</option>
        <option value="Majin">Majin</option>
    </select>
    
    <!-- Bouton de recherche stylisé -->
    <input type="submit" class="styled-button" name="search_btn" value="Rechercher">
</form>

<div class="content">
<div class="character-list">
    <?php foreach ($characters as $character): ?>
        <div class="character-card">
            <h2><?php echo htmlspecialchars($character['name']); ?></h2>
            <img src="<?php echo htmlspecialchars($character['image']); ?>" alt="<?php echo htmlspecialchars($character['name']); ?>" />
            <p><strong>Race :</strong> <?php echo htmlspecialchars($character['race']); ?></p>
            <p><strong>Genre :</strong> <?php echo htmlspecialchars($character['gender']); ?></p>
         

            <?php
            $characterName = $character['name'];
            $characterRace = $character['race'];
            $cost = isset($characterCosts[$characterRace]) ? $characterCosts[$characterRace] : 90;

            if ($characterName === 'Piccolo') {
                $cost = 9000000;
            } elseif ($characterName === 'Zeno') {
                $cost = 10000000000;
            } elseif ($characterName === 'Bills') {
                $cost = 100000;
            } elseif ($characterName === 'Gogeta' || $characterName === 'Vegetto') {
                $cost = 2000000;
            }
            ?>

            <form method="POST" action="">
                <input type="hidden" name="character" value='<?php echo json_encode($character); ?>'>
                <button type="submit">Acheter - <?php echo $cost; ?> Senzus</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
</div>
</body>
</html>
