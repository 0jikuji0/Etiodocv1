<?php
session_start();

if (!isset($_GET['id'])) {
    echo "Aucun patient sélectionné.";
    exit();
}

$patients = $_SESSION['patients'] ?? [];
$patient = null;

foreach ($patients as $p) {
    if ($p['id'] === $_GET['id']) {
        $patient = $p;
        break;
    }
}

if (!$patient) {
    echo "Patient introuvable.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du patient</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow max-w-md w-full">
        <h1 class="text-2xl font-bold mb-4"><?php echo htmlspecialchars($patient['name']); ?></h1>
        <p><strong>Âge:</strong> <?php echo htmlspecialchars($patient['age']); ?></p>
        <p><strong>Sexe:</strong> <?php echo htmlspecialchars($patient['sex']); ?></p>
        <p><strong>Notes:</strong> <?php echo nl2br(htmlspecialchars($patient['notes'])); ?></p>
        <p class="text-sm text-gray-500 mt-4">Ajouté le <?php echo $patient['created_at']; ?></p>

        <button onclick="window.history.back()" class="mt-6 bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded">Retour</button>
    </div>
</body>
</html>
