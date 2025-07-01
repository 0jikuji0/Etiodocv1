<?php
session_start();

$patients = $_SESSION['patients'] ?? [];
$id = $_GET['id'] ?? null;

$patient = null;
foreach ($patients as $p) {
    if ($p['id'] === $id) {
        $patient = $p;
        break;
    }
}

if (!$patient) {
    echo "Patient non trouvé.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Patient</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow max-w-md w-full">
        <h1 class="text-2xl font-bold mb-6">Détails du Patient</h1>
        <p><strong>Nom :</strong> <?php echo htmlspecialchars($patient['name']); ?></p>
        <p><strong>Âge :</strong> <?php echo htmlspecialchars($patient['age']); ?></p>
        <p><strong>Sexe :</strong> <?php echo htmlspecialchars($patient['sex']); ?></p>
        <p><strong>Date d'ajout :</strong> <?php echo htmlspecialchars($patient['created_at']); ?></p>
        <p class="mt-4"><strong>Notes :</strong><br><?php echo nl2br(htmlspecialchars($patient['notes'])); ?></p>

        <a href="/index.php" class="mt-6 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded font-medium">
            Retour à l'accueil
        </a>
    </div>
</body>
</html>
