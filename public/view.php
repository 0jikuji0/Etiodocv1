<?php
session_start();

// Vérification de la session
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Vérification que l'ID du patient est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Erreur : Aucun patient sélectionné.";
    echo '<br><a href="index.php">Retour à l\'accueil</a>';
    exit();
}

// Récupération des patients depuis la session
$patients = $_SESSION['patients'] ?? [];
$patient = null;

// Recherche du patient par ID
foreach ($patients as $p) {
    // Vérification que le patient a bien un ID (pour éviter les erreurs avec les anciens patients)
    if (isset($p['id']) && $p['id'] === $_GET['id']) {
        $patient = $p;
        break;
    }
}

// Si le patient n'est pas trouvé
if (!$patient) {
    echo "Erreur : Patient introuvable avec l'ID : " . htmlspecialchars($_GET['id']);
    echo '<br><br>Patients disponibles :<br>';
    foreach ($patients as $p) {
        if (isset($p['id'])) {
            echo "- " . htmlspecialchars($p['name']) . " (ID: " . htmlspecialchars($p['id']) . ")<br>";
        }
    }
    echo '<br><a href="index.php">Retour à l\'accueil</a>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etiodoc - Détails du patient</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-8">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-blue-500 rounded flex items-center justify-center">
                        <i class="fas fa-stethoscope text-white text-sm"></i>
                    </div>
                    <span class="text-xl font-semibold text-gray-800">etiodoc</span>
                </div>
            </div>
            <button onclick="window.location.href='index.php'" class="text-gray-600 hover:text-gray-800 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à l'accueil
            </button>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-6 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 font-semibold text-2xl">
                        <?php
                            $names = explode(' ', $patient['name']);
                            $initials = strtoupper(substr($names[0],0,1) . (isset($names[1]) ? substr($names[1],0,1) : ''));
                            echo $initials;
                        ?>
                    </span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($patient['name']); ?></h1>
                    <p class="text-gray-500">Patient #<?php echo htmlspecialchars($patient['id']); ?></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Âge</label>
                    <p class="text-lg text-gray-800"><?php echo htmlspecialchars($patient['age']); ?> ans</p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sexe</label>
                    <p class="text-lg text-gray-800"><?php echo htmlspecialchars($patient['sex']); ?></p>
                </div>
            </div>

            <?php if (!empty($patient['notes'])): ?>
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes médicales</label>
                <div class="text-gray-800 whitespace-pre-wrap"><?php echo htmlspecialchars($patient['notes']); ?></div>
            </div>
            <?php endif; ?>

            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Ajouté le <?php echo $patient['created_at']; ?>
                </p>
                
                <div class="flex space-x-3">
                    <button onclick="window.history.back()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Retour
                    </button>
                    <button onclick="window.location.href='accueil.php'" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-home mr-1"></i>
                        Accueil
                    </button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>