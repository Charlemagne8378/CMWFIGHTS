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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
    <style>
        #confirmation-dialog {
            position: fixed;
            top: 50%;
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
            background-color: #dc3545;
            color: #fff;
        }

        #cancel-btn {
            background-color: #6c757d;
            color: #fff;
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
                <table class="table table-striped">
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

            <div id="ajouter-utilisateur-form-container" class="hidden mt-4">
                <h2>Ajouter un utilisateur</h2>
                <form id="ajouter-utilisateur-form" method="post" action="../process/traiter_ajout_utilisateur.php">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="pseudo">Pseudo</label>
                            <input type="text" name="pseudo" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="nom">Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="email">Adresse email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="type">Type</label>
                            <select name="type" class="form-control" required>
                                <option value="admin">Admin</option>
                                <option value="moderateur">Modérateur</option>
                                <option value="utilisateur">Utilisateur</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="mot_de_passe">Mot de passe</label>
                            <input type="password" name="mot_de_passe" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <input type="submit" name="ajouter_utilisateur" value="Confirmer" class="btn btn-success mt-3">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="confirmation-dialog" class="hidden">
            <p><input type="text" class="w-50 form-control" id="confirmation-input"></p>
            <button type="button" id="confirm-btn" class="btn btn-danger btn-sm">Confirmer</button>
            <button type="button" id="cancel-btn" class="btn btn-secondary btn-sm">Annuler</button>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <a href="admin" class="btn btn-secondary">Retour</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.supprimer-btn').click(function() {
                const email = $(this).data('email');
                const confirmationDialog = $('#confirmation-dialog');
                const confirmationInput = $('#confirmation-input');
                const confirmBtn = $('#confirm-btn');
                const cancelBtn = $('#cancel-btn');

                confirmationDialog.removeClass('hidden');
                confirmationInput.val('');

                confirmBtn.off('click').on('click', () => {
                    if (confirmationInput.val().toUpperCase() === 'SUPPRIMER') {
                        supprimerUtilisateur(email);
                        confirmationDialog.addClass('hidden');
                    } else {
                        alert('Veuillez entrer "SUPPRIMER" pour confirmer la suppression.');
                    }
                });

                cancelBtn.off('click').on('click', () => {
                    confirmationDialog.addClass('hidden');
                });
            });

            $('#ajouter-utilisateur-btn').click(() => {
                $('#ajouter-utilisateur-form-container').toggleClass('hidden');
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
                        $(`tr:has(td:contains(${email}))`).remove();
                    } else {
                        console.error('Une erreur est survenue lors de la suppression de l\'utilisateur.');
                    }
                } catch (error) {
                    console.error('Une erreur est survenue lors de la suppression de l\'utilisateur :', error);
                }
            }

            $('table').on('click', 'th.sortable', function() {
                const columnIndex = $(this).data('column');
                const rows = $('tbody tr').get();
                const isSortedAsc = $(this).hasClass('sorted-asc');

                rows.sort((a, b) => {
                    const aValue = $(a).children('td').eq(columnIndex - 1).text().trim();
                    const bValue = $(b).children('td').eq(columnIndex - 1).text().trim();

                    if (columnIndex === 5) {
                        const aValueDate = new Date(aValue);
                        const bValueDate = new Date(bValue);

                        return isSortedAsc
                            ? aValueDate - bValueDate
                            : bValueDate - aValueDate;
                    } else {
                        return isSortedAsc
                            ? aValue.localeCompare(bValue)
                            : bValue.localeCompare(aValue);
                    }
                });

                $(this).toggleClass('sorted-asc sorted-desc');

                $.each(rows, (index, row) => {
                    $('tbody').append(row);
                });
            });
        });
    </script>
</body>
</html>
