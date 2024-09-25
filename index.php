<?php

require_once('./header.php'); 

// Vérifier si l'utilisateur est connecté
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);

    // Vérification des données reçues
    if (!isset($data['user_id'], $data['character']['name'], $data['character']['race'], $data['character']['gender'], $data['character']['description'], $data['character']['image'])) {
        echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
        exit();
    }

    // Récupérer les données du personnage et de l'utilisateur
    $user_id = $data['user_id'];
    $character_name = $data['character']['name'];
    $character_race = $data['character']['race'];
    $character_gender = $data['character']['gender'];
    $character_description = $data['character']['description'];
    $character_image = $data['character']['image'];

    try {
        // Insérer le personnage dans la base de données
        $stmt = $conn->prepare("INSERT INTO user_characters (user_id, character_name, character_race, character_gender, character_description, character_image) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $character_name, $character_race, $character_gender, $character_description, $character_image]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement du personnage : ' . $e->getMessage()]);
    }
    exit();  
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personnages Dragon Ball</title>
    <link rel="stylesheet" href="./public/css/index.css">
    <script src="./public/js/index.js" defer></script>
</head>
<body>

    <h1>Dragon Ball Collection</h1>

    <input type="hidden" id="userId" value="<?php echo $_SESSION['user_id']; ?>">
<div class="imagede_fond">
    <img src="./public/img/dragonball1ereseriepl_.jpg" alt="">
  
    </div>
<div class="imagefond"><img class="planche" src="./public/img/35565-planche-bd-dragon-ball.jpg" alt=""></div>
   
    <div class="container">
        <div id="invocationGif" class="invocation_gif" hidden>
            <img src="./public/img/shenrongif.webp" alt="Invocation">
        </div>
        <button id="generateBtn" class="generate-btn">Invoquer</button>
    </div>

    <div id="randomCharacter" class="character-container"></div>

</body>
</html>

