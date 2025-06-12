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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etiodoc - Gestion des Patients</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                    <a href="#" class="text-gray-600 hover:text-gray-800 font-medium pb-4">Nouveau Patient</a>
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
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Bienvenue, Dr <?php echo htmlspecialchars($doctor_name); ?></h1>
            
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2 transition-colors">
                <i class="fas fa-plus"></i>
                <span>Ajouter une nouvelle note</span>
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Patients récents</h2>
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="Recherche" 
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    >
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <div class="divide-y divide-gray-200">
                <div class="p-6 hover:bg-gray-50 cursor-pointer transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-semibold text-lg">MD</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">Marie Dupont</h3>
                            <p class="text-sm text-gray-500">Dernière note - Aujourd'hui</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </div>

                <div class="p-6 hover:bg-gray-50 cursor-pointer transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden">
                            <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                <span class="text-white font-semibold text-lg">JM</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">Jean Martin</h3>
                            <p class="text-sm text-gray-500">Dernière note - Hier</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </div>

                <div class="p-6 hover:bg-gray-50 cursor-pointer transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden">
                            <div class="w-full h-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                                <span class="text-white font-semibold text-lg">SL</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">Sophie Lefevre</h3>
                            <p class="text-sm text-gray-500">Dernière note - 20 avril 2024</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </div>

                <div class="p-6 hover:bg-gray-50 cursor-pointer transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden">
                            <div class="w-full h-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
                                <span class="text-white font-semibold text-lg">PD</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">Pierre Dubois</h3>
                            <p class="text-sm text-gray-500">Dernière note - 18 avril 2024</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function logout() {
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                window.location.href = 'logout.php';
            }
        }

        document.querySelectorAll('[class*="hover:"]').forEach(element => {
            element.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 100);
            });
        });

        const searchInput = document.querySelector('input[placeholder="Recherche"]');
        const patientItems = document.querySelectorAll('.divide-y > div');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            patientItems.forEach(item => {
                const patientName = item.querySelector('h3').textContent.toLowerCase();
                if (patientName.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.borderBottomColor = '#3B82F6';
                this.style.borderBottomWidth = '2px';
            });
            
            link.addEventListener('mouseleave', function() {
                if (!this.classList.contains('border-blue-500')) {
                    this.style.borderBottomWidth = '0px';
                }
            });
            
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('nav a').forEach(l => {
                    l.classList.remove('border-blue-500', 'border-b-2');
                    l.classList.add('pb-4');
                });
                this.classList.add('border-blue-500', 'border-b-2');
            });
        });

        setInterval(function() {
            fetch('check_session.php')
                .then(response => response.json())
                .then(data => {
                    if (!data.logged_in) {
                        alert('Votre session a expiré. Vous allez être redirigé vers la page de connexion.');
                        window.location.href = 'login.php';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la vérification de session:', error);
                });
        }, 300000); 
    </script>
</body>
</html>