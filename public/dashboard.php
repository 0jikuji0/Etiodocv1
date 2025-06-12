<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
</head>
<body>
    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['pseudo']); ?> !</h1>
    <p>Contenu privé ici.</p>
    <a href="logout.php">Se déconnecter</a>
</body>
</html>