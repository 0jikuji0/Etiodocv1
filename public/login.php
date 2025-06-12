<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: accueil.php');
    exit();
}

// $error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO('mysql:host=mysql;dbname=db;charset=utf8', 'user', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pseudo = trim($_POST['pseudo']);
        $mdp = $_POST['mdp'];

        if (empty($pseudo) || empty($mdp)) {
            $error_message = "Veuillez remplir tous les champs.";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE pseudo = ?");
            $stmt->execute([$pseudo]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($mdp, $user['mdp'])) {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['pseudo'] = $user['pseudo'];
                $_SESSION['id'] = $user['id'];
                $_SESSION['doctor_name'] = $user['pseudo']; 
                $_SESSION['last_activity'] = time();

                header('Location: accueil.php');
                exit();
            } else {
                $error_message = "Mauvais identifiant ou mot de passe.";
            }
        }
    } catch (PDOException $e) {
        $error_message = "Erreur de connexion à la base de données.";
        error_log("Erreur PDO: " . $e->getMessage());

        // Debug supplémentaire pour Docker (à retirer en production)
        if (ini_get('display_errors')) {
            $error_message .= " Détails: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_logged_in ? 'Etiodoc - Dashboard' : 'Etiodoc - Connexion'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100">
            <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-xl shadow-lg">
                <div class="text-center">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-stethoscope text-white text-2xl"></i>
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">etiodoc</h2>
                    <p class="mt-2 text-sm text-gray-600">Connectez-vous à votre espace médical</p>
                </div>

                <?php if (isset($error_message)): ?>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-800"><?php echo htmlspecialchars($error_message); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" class="mt-8 space-y-6">
                    <div class="space-y-4">
                        <div>
                            <label for="pseudo" class="block text-sm font-medium text-gray-700">Pseudo</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input
                                    id="pseudo"
                                    name="pseudo"
                                    type="text"
                                    required
                                    class="pl-10 appearance-none relative block w-full px-3 py-3 border border-gray-300 rounded-lg placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                    placeholder="Votre pseudo">
                            </div>
                        </div>

                        <div>
                            <label for="mdp" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input
                                    id="mdp"
                                    name="mdp"
                                    type="password"
                                    required
                                    class="pl-10 appearance-none relative block w-full px-3 py-3 border border-gray-300 rounded-lg placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                    placeholder="Votre mot de passe">
                            </div>
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-sign-in-alt text-blue-500 group-hover:text-blue-400"></i>
                            </span>
                            Se connecter
                        </button>
                    </div>
                </form>


                <div class="text-center">
                    <p class="text-xs text-gray-500">
                        © 2024 Etiodoc. Plateforme médicale sécurisée.
                    </p>
                </div>
            </div>
        </div>


    <script>
        <?php if ($is_logged_in): ?>
            const logoutLink = document.querySelector('[href="?logout=1"]');
            if (logoutLink) {
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                        window.location.href = '?logout=1';
                    }
                });
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

            if (searchInput && patientItems) {
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
            }

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
        <?php else: ?>
            const loginForm = document.querySelector('form');
            const inputs = document.querySelectorAll('input');
            const submitButton = document.querySelector('button[type="submit"]');

            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('scale-105');
                    this.parentElement.style.transition = 'transform 0.2s ease';
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('scale-105');
                });
            });

            submitButton.addEventListener('click', function() {
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Connexion...';
                this.disabled = true;

                setTimeout(() => {
                    this.innerHTML = '<span class="absolute left-0 inset-y-0 flex items-center pl-3"><i class="fas fa-sign-in-alt text-blue-500 group-hover:text-blue-400"></i></span>Se connecter';
                    this.disabled = false;
                }, 3000);
            });

            const loginContainer = document.querySelector('.max-w-md');
            loginContainer.style.opacity = '0';
            loginContainer.style.transform = 'translateY(20px)';

            setTimeout(() => {
                loginContainer.style.transition = 'all 0.5s ease';
                loginContainer.style.opacity = '1';
                loginContainer.style.transform = 'translateY(0)';
            }, 100);
        <?php endif; ?>
    </script>

    <?php
    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    ?>
</body>

</html>