<?php
require_once '../require/config/config.php';
require_once '../require/sidebar.php';
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
    <link rel="stylesheet" href="../style/sidebar.css">
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
    </style>
</head>
<body>
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