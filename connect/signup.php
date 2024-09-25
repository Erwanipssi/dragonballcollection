<?php 
require_once('../dbconnect/dbconnect.php');

$error = ""; 

if(isset($_POST['submit'])){
 
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

   
    $pseudo = htmlspecialchars($pseudo);
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'email n'est pas au bon format";
    }

    if(!empty($pseudo) && !empty($email) && !empty($password) && !empty($confirm)) {
     
        if($password === $confirm) {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try {
              
                $sql = "INSERT INTO user (pseudo, mail, password) VALUES (:pseudo, :email, :password)";
                $stmt = $conn->prepare($sql);
                
              
                $stmt->execute([
                    ':pseudo' => $pseudo,
                    ':email' => $email,
                    ':password' => $hashed_password
                ]);

                echo "Inscription réussie !";

            } catch (PDOException $e) {
                echo "Erreur lors de l'inscription : " . $e->getMessage();
            }

        } else {
            $error = "Les mots de passe ne correspondent pas.";
        }
        
    } else {
        $error = "Veuillez remplir tous les champs.";
    }

    if (!empty($error)) {
        echo "<div class='alert alert-danger'>$error</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/login.css">
</head>
<body>
<div class="container">
<div class="container mt-5" style="background-color : white; width: 600px; border-radius:10px; height: 650px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Inscription</h2>
            <p>un commpte? <a href="./login.php">connecte toi</a></p>
            <form action="signup.php" method="post">
                <div class="mb-3">
                    <label for="pseudo" class="form-label">Pseudo</label>
                    <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Entrez votre pseudo" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                </div>
                <div class="mb-3">
                    <label for="confirm" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" id="confirm" name="confirm" placeholder="Confirmez votre mot de passe" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" name="submit">S'inscrire</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
