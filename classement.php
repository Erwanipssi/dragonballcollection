<?php 
require_once('./header.php');

$sql = "
    SELECT u.pseudo, COUNT(uc.id) AS card_count
    FROM user u
    LEFT JOIN user_characters uc ON u.id = uc.user_id
    GROUP BY u.pseudo
    ORDER BY card_count DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement des utilisateurs</title>
    <link rel="stylesheet" href="./public/css/classement.css">
</head>
<body>

    <h1>Classement des utilisateurs par nombre de cartes</h1>
    
    <table>
        <thead>
            <tr>
                <th>Position</th>
                <th>Utilisateur</th>
                <th>Nombre de cartes</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $position = 1;
            foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $position++; ?></td>
                    <td><?php echo htmlspecialchars($user['pseudo']); ?></td>
                    <td><?php echo htmlspecialchars($user['card_count']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
