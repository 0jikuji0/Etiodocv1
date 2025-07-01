<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location: login.php?message=session_expired');
    exit();
}

$_SESSION['last_activity'] = time();

$doctor_name = isset($_SESSION['doctor_name']) ? $_SESSION['doctor_name'] : 'Docteur';

// Traitement de la suppression
if (isset($_POST['delete_patient']) && isset($_POST['patient_id'])) {
    $patient_id = $_POST['patient_id'];
    $patients = $_SESSION['patients'] ?? [];

    // Trouver et supprimer le patient
    foreach ($patients as $key => $patient) {
        if ($patient['id'] == $patient_id) {
            unset($patients[$key]);
            break;
        }
    }

    // Réindexer le tableau
    $_SESSION['patients'] = array_values($patients);

    // Redirection pour éviter la resoumission
    header('Location: ' . $_SERVER['PHP_SELF'] . '?deleted=1');
    exit();
}

// Récupération des patients en session
$patients = $_SESSION['patients'] ?? [];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Etiodoc - Gestion des Patients</title>
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

                <nav class="flex items-center space-x-6">
                    <a href="#" class="text-gray-600 hover:text-gray-800 font-medium border-b-2 border-blue-500 pb-4">Accueil</a>
                    <a href="#" class="text-gray-600 hover:text-gray-800 font-medium pb-4">Mes Notes</a>
                    <a href="nouveau_patient.php" class="text-gray-600 hover:text-gray-800 font-medium pb-4">Nouveau Patient</a>
                    <a href="#" class="text-gray-600 hover:text-gray-800 font-medium pb-4">Paramètres</a>
                </nav>
            </div>

            <div class="flex items-center space-x-4">
                <span class="text-gray-600">Connecté en tant que: <?php echo htmlspecialchars($doctor_name); ?></span>
                <button onclick="logout()" class="text-red-600 hover:text-red-800 font-medium">
                    <i class="fas fa-sign-out-alt mr-1"></i>
                    Déconnexion
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-8">
        <?php if (isset($_GET['deleted'])): ?>
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>
                Patient supprimé avec succès.
            </div>
        <?php endif; ?>

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Bienvenue, Dr <?php echo htmlspecialchars($doctor_name); ?></h1>

            <button
                onclick="window.location.href='/pages/patients/create.php'"
                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2 transition-colors">
                <i class="fas fa-plus"></i>
                <span>Ajouter un patient</span>
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Patients récents</h2>
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Recherche"
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <div class="divide-y divide-gray-200">
                <?php if (empty($patients)): ?>
                    <div class="p-6 text-gray-500">Aucun patient enregistré pour l'instant.</div>
                <?php else: ?>
                    <?php foreach (array_reverse($patients) as $patient): ?>
                        <div class="p-6 hover:bg-gray-50 transition-colors patient-item">
                            <div class="flex items-center justify-between">
                                <a href="view.php?id=<?php echo urlencode($patient['id']); ?>" class="flex items-center space-x-4 flex-1">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold text-lg">
                                            <?php
                                            $names = explode(' ', $patient['name']);
                                            $initials = strtoupper(substr($names[0], 0, 1) . (isset($names[1]) ? substr($names[1], 0, 1) : ''));
                                            echo $initials;
                                            ?>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800 patient-name"><?php echo htmlspecialchars($patient['name']); ?></h3>
                                        <p class="text-sm text-gray-500">Ajouté le <?php echo $patient['created_at']; ?></p>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400"></i>
                                </a>

                                <div class="ml-4 flex items-center space-x-2">
                                    <button
                                        onclick="confirmDelete('<?php echo htmlspecialchars($patient['id']); ?>', '<?php echo htmlspecialchars($patient['name']); ?>')"
                                        class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                        title="Supprimer ce patient">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <!-- Modal de confirmation de suppression -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Confirmer la suppression</h3>
                        <p class="text-sm text-gray-600">Cette action est irréversible</p>
                    </div>
                </div>

                <p class="text-gray-700 mb-6">
                    Êtes-vous sûr de vouloir supprimer le patient <strong id="patientNameToDelete"></strong> ?
                </p>

                <div class="flex space-x-3 justify-end">
                    <button
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">
                        Annuler
                    </button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="patient_id" id="patientIdToDelete">
                        <button
                            type="submit"
                            name="delete_patient"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function logout() {
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                window.location.href = 'logout.php';
            }
        }

        function confirmDelete(patientId, patientName) {
            document.getElementById('patientIdToDelete').value = patientId;
            document.getElementById('patientNameToDelete').textContent = patientName;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Fermer la modal en cliquant en dehors
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Recherche de patients
        const searchInput = document.querySelector('input[placeholder="Recherche"]');
        const patientItems = document.querySelectorAll('.patient-item');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            patientItems.forEach(item => {
                const patientName = item.querySelector('.patient-name') ? item.querySelector('.patient-name').textContent.toLowerCase() : '';
                if (patientName.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Masquer le message de succès après 5 secondes
        setTimeout(function() {
            const successMessage = document.querySelector('.bg-green-100');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 5000);
    </script>
</body>

</html>