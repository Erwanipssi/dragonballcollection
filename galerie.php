<?php

require_once('./header.php'); 


if (!isset($_SESSION['user_id'])) {
    echo "Pas connecté chef.";
    exit();
}

$user_id = $_SESSION['user_id']; 

$goldRewards = [
    'Human' => 33,
    'Frieza Race' => 70,
    'Android' => 50,
    'Namekian' => 500,
    'Saiyan' => 120,
    'Angel' => 250,
    'God' => 200,
    'Evil' => 220,
    'Nucleico Benigno' => 170,
    'Nucleico' => 160,
    'Jiren Race' => 190,
    'Majin' => 160
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['character_id'])) {
    $character_id = $_POST['character_id'];

 
    $characterSql = "SELECT character_race FROM user_characters WHERE id = :character_id AND user_id = :user_id";
    $stmt = $conn->prepare($characterSql);
    $stmt->bindParam(':character_id', $character_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $character = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($character) {
        $race = $character['character_race'];

        $goldEarned = isset($goldRewards[$race]) ? $goldRewards[$race] : 800; 

     
        $deleteSql = "DELETE FROM user_characters WHERE id = :character_id AND user_id = :user_id";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bindParam(':character_id', $character_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

      
        $updateGoldSql = "UPDATE user SET gold = gold + :gold WHERE id = :user_id";
        $stmt = $conn->prepare($updateGoldSql);
        $stmt->bindParam(':gold', $goldEarned, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: galerie.php");
        exit();
    } else {
        echo "Personnage non trouvé ou non autorisé.";
        exit();
    }
}


$sql = "SELECT user_characters.*, user.pseudo, user.gold
        FROM user_characters 
        INNER JOIN user ON user_characters.user_id = user.id 
        WHERE user_characters.user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$characters = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie</title>
    <link rel="stylesheet" href="./public/css/galerie.css">
</head>
<body>

<div class="gold-display">
    <img src="./public/img/senzu.png" alt="Gold" />
    <span><?php echo $characters[0]['gold']; ?></span>
</div>

<h1>Votre liste de personnages :</h1>

<?php if (!empty($characters)): ?>
    <div class="character-list">
        <?php foreach ($characters as $character): ?>
            <div class="character-card">
                <h2><?php echo htmlspecialchars($character['character_name']); ?></h2>
                <img src="<?php echo htmlspecialchars($character['character_image']); ?>" alt="<?php echo htmlspecialchars($character['character_name']); ?>" />
                <p><strong>Race :</strong> <?php echo htmlspecialchars($character['character_race']); ?></p>
                <p><strong>Genre :</strong> <?php echo htmlspecialchars($character['character_gender']); ?></p>
             
                <form method="POST" action="">
    <input type="hidden" name="character_id" value="<?php echo $character['id']; ?>">
    <button type="submit" class="delete_button">Vendre</button>
    <p class="gold-info"> 
        <?php 
            $race = $character['character_race'];
            echo isset($goldRewards[$race]) ? htmlspecialchars($goldRewards[$race]) : 30; 
        ?> golds
    </p>
</form>

            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>tu as pas de persos va invoquer</p>
<?php endif; ?>

</body>
</html>
