<?php
require_once '../config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

$stmt = $pdo->prepare('SELECT pseudo, nom, adresse_email, type, derniere_connexion FROM UTILISATEUR');
$stmt->execute();
$utilisateurs = $stmt->fetchAll();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM UTILISATEUR WHERE type != "invite"');
$stmt->execute();
$utilisateurs_inscrits = $stmt->fetchColumn();

$pdo = null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Administration</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        #confirmation-dialog {
            position: fixed;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            text-align: center;
        }

        #confirmation-input {
            margin-bottom: 10px;
        }

        #confirm-btn,
        #cancel-btn {
            padding: 5px 10px;
            margin: 0 5px;
            cursor: pointer;
            border: none;
            border-radius: 3px;
        }

        #confirm-btn {
            background-color: #007bff;
            color: #fff;
        }

        #cancel-btn {
            background-color: #ccc;
        }

        .hidden {
            display: none;
        }

        table th.sortable {
            cursor: pointer;
            user-select: none;
        }

        table th.sortable:hover {
            text-decoration: underline;
        }

        .actions-column {
            width: 20%;
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding: 16px 0;
            box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.2);
            width: 280px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar .nav-link {
            color: #333;
            white-space: nowrap;
            margin-bottom: 0.5rem;
        }

        .sidebar .nav-link i {
            margin-right: 0px;
        }

        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar.collapsed .nav-link {
            padding-left: 15px;
            padding-right: 15px;
            font-size: 0;
            text-align: center;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 18px;
        }

        .sidebar .nav-link.active {
            color: #007bff;
            background-color: rgba(0, 123, 255, 0.1);
        }

        .main-content {
            transition: margin-left 0.3s ease;
            margin-left: 280px;
        }

        .main-content.collapsed {
            margin-left: 60px;
        }

        .account-box {
            position: absolute;
            bottom: 60px; 
            left: 0;
            width: 100%;
            background-color: #f8f9fa;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
            display: none;
        }

        .account-box.show {
            display: block;
        }

        .account-box a {
            color: #333;
            display: block;
            padding: 0.5rem 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .account-box a:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }

        .account-btn {
            position: absolute;
            bottom: 10px;
            left: 0;
            width: 100%;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                padding: 0;
            }

            .sidebar.collapsed {
                width: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }

        @media (min-width: 769px) {
            .toggle-sidebar {
                display: none;
            }
        }
    </style>
</head>
<body>
<nav class="sidebar">
    <div class="text-center mb-3">
        <img src="../Images/cmwnoir.png" alt="Logo" style="width: 128px; height: 128px;">
    </div>
    <a class="nav-link" href="admin">
        <i class="bi bi-house-door"></i>
        <span class="ml-2 d-none d-sm-inline">Admin</span>
    </a>
    <a class="nav-link active" href="utilisateurs">
        <i class="bi bi-person-lines-fill"></i>
        <span class="ml-2 d-none d-sm-inline">Utilisateurs</span>
    </a>
    <a class="nav-link" href="evenements">
        <i class="bi bi-calendar-event"></i>
        <span class="ml-2 d-none d-sm-inline">Événements</span>
    </a>
    <a class="nav-link" href="modifier_utilisateur">
        <i class="bi bi-pencil-square"></i>
        <span class="ml-2 d-none d-sm-inline">Modifier le compte</span>
    </a>
    <a class="nav-link" href="classement">
        <i class="bi bi-bar-chart"></i>
        <span class="ml-2 d-none d-sm-inline">Classement</span>
    </a>
    <a class="nav-link" href="combattants">
        <i class="bi bi-people"></i>
        <span class="ml-2 d-none d-sm-inline">Combattants</span>
    </a>
    <a class="nav-link" href="candidature">
        <i class="bi bi-file-earmark-text"></i>
        <span class="ml-2 d-none d-sm-inline">Candidature</span>
    </a>
    <a class="nav-link" href="billetterie">
        <i class="bi bi-ticket"></i>
        <span class="ml-2 d-none d-sm-inline">Billetterie</span>
    </a>
    <a class="nav-link" href="service_client">
        <i class="bi bi-telephone"></i>
        <span class="ml-2 d-none d-sm-inline">Service Client</span>
    </a>
    <a class="nav-link" href="image">
        <i class="bi bi-image"></i>
        <span class="ml-2 d-none d-sm-inline">Image</span>
    </a>
    <a class="nav-link" href="newsletters">
        <i class="bi bi-envelope"></i>
        <span class="ml-2 d-none d-sm-inline">Newsletters</span>
    </a>
    <a class="nav-link" href="captcha">
        <i class="bi bi-shield-lock"></i>
        <span class="ml-2 d-none d-sm-inline">Captcha</span>
    </a>
    <a class="nav-link" href="accueil">
        <i class="bi bi-house-door"></i>
        <span class="ml-2 d-none d-sm-inline">Accueil</span>
    </a>
    <a class="nav-link" href="logs">
        <i class="bi bi-journal"></i>
        <span class="ml-2 d-none d-sm-inline">Logs</span>
    </a>
    <a class="nav-link" href="permissions">
        <i class="bi bi-shield-lock"></i>
        <span class="ml-2 d-none d-sm-inline">Permissions utilisateurs</span>
    </a>
    <a class="nav-link" href="bdd">
        <i class="bi bi-gear"></i>
        <span class="ml-2 d-none d-sm-inline">Base de données</span>
    </a>

    <div class="account-box">
        <a href="../compte/settings">Paramètres</a>
        <a href="../auth/logout.php">Déconnexion</a>
    </div>
    <button class="btn btn-primary btn-block account-btn">
        Compte
    </button>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-12 mx-auto">
            <h1 class="mb-4">Administration</h1>

        <h2>Statistiques</h2>
        <p>Nombre total d'utilisateurs inscrits : <?= htmlspecialchars($utilisateurs_inscrits) ?></p>

        <h2 class="mb-3">Liste des utilisateurs</h2>
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th class="sortable" data-column="1">Pseudo</th>
                        <th class="sortable" data-column="2">Nom</th>
                        <th class="sortable" data-column="3">Adresse email</th>
                        <th class="sortable" data-column="4">Type</th>
                        <th class="sortable" data-column="5">Dernière connexion</th>
                        <th class="text-center actions-column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($utilisateurs as $utilisateur): ?>
                    <tr>
                        <td><?= htmlspecialchars($utilisateur['pseudo']) ?></td>
                        <td><?= htmlspecialchars($utilisateur['nom']) ?></td>
                        <td><?= htmlspecialchars($utilisateur['adresse_email']) ?></td>
                        <td><?= htmlspecialchars($utilisateur['type']) ?></td>
                        <td><?= htmlspecialchars($utilisateur['derniere_connexion'] ?? '') ?></td>
                        <td class="text-center actions-column">
                            <?php if ($utilisateur['type'] !== 'admin'): ?>
                                <a href="modifier_utilisateur.php?pseudo=<?php echo urlencode($utilisateur['pseudo']); ?>" class="btn btn-primary btn-sm">Modifier</a>
                                <button type="button" class="btn btn-danger btn-sm supprimer-btn" data-email="<?= htmlspecialchars($utilisateur['adresse_email']) ?>">Supprimer</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
                            
        <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-success" id="ajouter-utilisateur-btn">Ajouter un utilisateur</button>
        </div>

        <div id="ajouter-utilisateur-form-container" class="hidden">
            <h2>Ajouter un utilisateur</h2>
            <form id="ajouter-utilisateur-form" method="post" action="../process/traiter_ajout_utilisateur.php">
                <div class="form-row d-flex justify-content-between align-items-center">
                    <div class="col-md-2 form-group">
                        <label for="pseudo">Pseudo</label>
                        <input type="text" name="pseudo" class="form-control" required>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="type">Type</label>
                        <select name="type" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="moderateur">Modérateur</option>
                            <option value="utilisateur">Utilisateur</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="mot_de_passe">Mot de passe</label>
                        <input type="password" name="mot_de_passe" class="form-control">
                    </div>
                </div>
                <div class="form-row d-flex justify-content-center">
                    <input type="submit" name="ajouter_utilisateur" value="Confirmer" class="btn btn-success mt-3">
                </div>
            </form>
        </div>
    </div>

    <div id="confirmation-dialog" class="hidden">
        <p><input type="text" class="w-50" id="confirmation-input"></p>
        <button type="button" id="confirm-btn" class="btn btn-danger btn-sm">Confirmer</button>
        <button type="button" id="cancel-btn" class="btn btn-secondary btn-sm">Annuler</button>
    </div>
    <div class="d-flex justify-content-center">
        <a href="admin" class="btn btn-secondary">Retour</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.modifier-question').click(function() {
                const questionId = $(this).data('id');
                const question = $(this).data('question');
                const answer = $(this).data('answer');

                $('#modal_question_id').val(questionId);
                $('#modal_question').val(question);
                $('#modal_answer').val(answer);
            });

            $('.account-btn').click(function() {
                $('.account-box').toggleClass('show');
            });
        });

        document.querySelectorAll('.supprimer-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const email = e.target.getAttribute('data-email');
                const confirmationDialog = document.getElementById('confirmation-dialog');
                const confirmationInput = document.getElementById('confirmation-input');
                const confirmBtn = document.getElementById('confirm-btn');
                const cancelBtn = document.getElementById('cancel-btn');

                confirmationDialog.classList.remove('hidden');
                confirmationInput.value = '';

                confirmBtn.addEventListener('click', () => {
                    if (confirmationInput.value.toUpperCase() === 'SUPPRIMER') {
                        supprimerUtilisateur(email);
                        confirmationDialog.classList.add('hidden');
                    } else {
                        alert('Veuillez entrer "SUPPRIMER" pour confirmer la suppression.');
                    }
                });

                cancelBtn.addEventListener('click', () => {
                    confirmationDialog.classList.add('hidden');
                });
            });
        });

        document.getElementById('ajouter-utilisateur-btn').addEventListener('click', () => {
            const ajouterUtilisateurFormContainer = document.getElementById('ajouter-utilisateur-form-container');
            ajouterUtilisateurFormContainer.classList.toggle('hidden');
        });

        async function supprimerUtilisateur(email) {
            try {
                const response = await fetch('../process/supprimer_utilisateur.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `Adresse_email=${encodeURIComponent(email)}`
                });

                if (response.ok) {
                    const row = document.querySelector(`tr input[type="email"][value="${email}"]`).closest('tr');
                    row.remove();
                } else {
                    console.error('Une erreur est survenue lors de la suppression de l\'utilisateur.');
                }
            } catch (error) {
                console.error('Une erreur est survenue lors de la suppression de l\'utilisateur :', error);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const table = document.querySelector('table');
            const headers = table.querySelectorAll('th.sortable');

            headers.forEach(header => {
                header.addEventListener('click', () => {
                    const columnIndex = header.dataset.column;
                    const rows = Array.from(table.querySelectorAll('tbody tr'));
                    const isSortedAsc = header.classList.contains('sorted-asc');

                    rows.sort((a, b) => {
                        const aValue = a.children[columnIndex - 1].textContent.trim();
                        const bValue = b.children[columnIndex - 1].textContent.trim();

                        if (columnIndex === '5') {
                            const aValueDate = new Date(aValue);
                            const bValueDate = new Date(bValue);

                            return isSortedAsc
                                ? aValueDate.getTime() - bValueDate.getTime()
                                : bValueDate.getTime() - aValueDate.getTime();
                        } else {
                            return isSortedAsc
                                ? aValue.localeCompare(bValue)
                                : bValue.localeCompare(aValue);
                        }
                    });

                    if (isSortedAsc) {
                        header.classList.remove('sorted-asc');
                        header.classList.add('sorted-desc');
                    } else {
                        header.classList.add('sorted-asc');
                        header.classList.remove('sorted-desc');
                    }

                    const tbody = table.querySelector('tbody');
                    tbody.innerHTML = '';
                    rows.forEach(row => tbody.appendChild(row));
                });
            });
        });
    </script>
</body>
</html>