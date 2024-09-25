<?php
session_start(); // Start the session
require_once('../dbconnect/dbconnect.php'); // Database connection

if (isset($_POST['submit'])) {
    $pseudo = $_POST['pseudo'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT * FROM user WHERE pseudo = :pseudo";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['pseudo' => $pseudo]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id']; 
                $_SESSION['pseudo'] = $user['pseudo']; 
                $_SESSION['logged_in'] = true; 
                header('Location: ../index.php'); 
                exit();
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Pseudo incorrect.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../public/css/login.css">
</head>
<body>

<div class="form_container">
    <img src="../public/img/5g3vqyyd.gif" class="gif" alt="shenron gif">
    <form action="login.php" method="post">
        <input type="text" name="pseudo" placeholder="Pseudo" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
      <p>pas de compte ? <a href="./signup.php"> inscris toi </a></p>  
        <input type="submit" name="submit" value="Se connecter">
    </form>
</div>

</body>
</html>
