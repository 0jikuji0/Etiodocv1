<?php
session_start();

// Vérifie si la requête est POST pour sauvegarder le patient
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';

    // Initialise la liste si elle n'existe pas
    if (!isset($_SESSION['patients'])) {
        $_SESSION['patients'] = [];
    }

    // Ajoute le patient à la session
    $_SESSION['patients'][] = [
        'name' => $name,
        'created_at' => date('Y-m-d H:i'),
    ];

    // Redirige vers l'accueil après l'ajout
    header('Location: /index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau Patient</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
<form action="" method="POST" class="bg-white p-8 rounded shadow max-w-md w-full">
    <h1 class="text-2xl font-bold mb-6">Ajouter un Patient</h1>
    
    <div class="mb-4">
        <label for="name" class="block text-gray-700">Nom du patient</label>
        <input type="text" name="name" id="name" required
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>

    <div class="mb-4">
        <label for="age" class="block text-gray-700">Âge</label>
        <input type="number" name="age" id="age" required
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>

    <div class="mb-4">
        <label for="sex" class="block text-gray-700">Sexe</label>
        <select name="sex" id="sex" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">-- Sélectionner --</option>
            <option value="Homme">Homme</option>
            <option value="Femme">Femme</option>
        </select>
    </div>

    <div class="mb-4">
        <label for="notes" class="block text-gray-700">Notes médicales</label>
        <textarea name="notes" id="notes"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
    </div>

    <button type="submit"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded font-medium">
        Sauvegarder
    </button>
</form>

</body>
</html>
