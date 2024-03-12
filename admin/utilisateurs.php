<?php
require_once '../config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['Type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

$stmt = $pdo->prepare('SELECT Pseudo, Nom, Adresse_email, Type FROM Utilisateurs');
$stmt->execute();
$utilisateurs = $stmt->fetchAll();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM Utilisateurs WHERE Type != "invite"');
$stmt->execute();
$utilisateurs_inscrits = $stmt->fetchColumn();

$pdo = null; // Fermer la connexion PDO
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Administration</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
#confirmation-dialog {
    position: fixed;
    top: 60%; /* Ajustez la position verticale selon vos préférences */
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    z-index: 9999;
    text-align: center; /* Centrer le texte et les boutons horizontalement */
}

#confirmation-input {
    margin-bottom: 10px; /* Espacement entre le champ de saisie et les boutons */
}

#confirm-btn,
#cancel-btn {
    padding: 5px 10px; /* Espacement intérieur des boutons */
    margin: 0 5px; /* Marge entre les boutons */
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

        .input-small {
            width: 80%;
        }

        .actions-column {
            width: 20%;
        }
    </style>
</head>
<body>
    <?php include '../pages/compo/header.php' ?>
    <div class="container mt-4">
        <h1 class="mb-4">Administration</h1>

        <h2>Statistiques</h2>
        <p>Nombre total d'utilisateurs inscrits : <?= htmlspecialchars($utilisateurs_inscrits) ?></p>

        <h2>Liste des utilisateurs</h2>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th class="sortable" data-column="1">Pseudo</th>
                    <th class="sortable" data-column="2">Nom</th>
                    <th class="sortable" data-column="3">Adresse email</th>
                    <th class="sortable" data-column="4">Type</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $utilisateur): ?>
                    <tr>
                        <td>
                            <span class="hidden"><?= htmlspecialchars($utilisateur['Pseudo']) ?></span>
                            <?= htmlspecialchars($utilisateur['Pseudo']) ?>
                        </td>
                        <td>
                            <span class="hidden"><?= htmlspecialchars($utilisateur['Nom']) ?></span>
                            <?= htmlspecialchars($utilisateur['Nom']) ?>
                        </td>
                        <td>
                            <span class="hidden"><?= htmlspecialchars($utilisateur['Adresse_email']) ?></span>
                            <?= htmlspecialchars($utilisateur['Adresse_email']) ?>
                        </td>
                        <td>
                            <span class="hidden"><?= htmlspecialchars($utilisateur['Type']) ?></span>
                            <?= htmlspecialchars($utilisateur['Type']) ?>
                        </td>
                        <td class="actions-column">
                            <?php if ($utilisateur['Type'] !== 'admin'): ?>
                                <button type="button" data-email="<?= htmlspecialchars($utilisateur['Adresse_email']) ?>" class="btn btn-primary btn-sm modifier-btn">Modifier</button>
                                <button type="button" class="btn btn-success btn-sm enregistrer-btn hidden">Enregistrer</button>
                                <button type="button" class="btn btn-danger btn-sm supprimer-btn" data-email="<?= htmlspecialchars($utilisateur['Adresse_email']) ?>">Supprimer</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Ajouter un utilisateur</h2>
        <form id="ajouter-utilisateur-form" method="post" action="../process/traiter_ajout_utilisateur.php">
        <div class="grid-container">
            <div class="form-row">
                <label for="pseudo">Pseudo</label>
                <input type="text" name="pseudo" class="input-small" required>
            </div>
            <div class="form-row">
                <label for="nom">Nom</label>
                <input type="text" name="nom" class="input-small" required>
            </div>
            <div class="form-row">
                <label for="email">Adresse email</label>
                <input type="email" name="email" class="input-small" required>
            </div>
            <div class="form-row">
                <label for="type">Type</label>
                <select name="type" class="input-small" required>
                    <option value="admin">Admin</option>
                    <option value="moderateur">Modérateur</option>
                    <option value="utilisateur">Utilisateur</option>
                </select>
            </div>
            <div class="form-row">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" name="mot_de_passe" class="input-small">
            </div>
            <div class="form-row">
                <input type="submit" name="ajouter_utilisateur" value="Ajouter l'utilisateur">
            </div>
        </div>
    </form>
    </div>

    <div id="confirmation-dialog" class="hidden">
        <p>Entrez "SUPPRIMER" pour confirmer la suppression :</p>
        <input type="text" id="confirmation-input">
        <button type="button" id="confirm-btn" class="btn btn-danger btn-sm">Confirmer</button>
        <button type="button" id="cancel-btn" class="btn btn-secondary btn-sm">Annuler</button>
    </div>
    <a href="admin" class="btn btn-secondary">Retour</a>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        document.querySelectorAll('.supprimer-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault();
        const email = e.target.closest('tr').querySelector('input[type="email"]').value;
        const confirmationDialog = document.getElementById('confirmation-dialog');
        const confirmationInput = document.getElementById('confirmation-input');
        const confirmBtn = document.getElementById('confirm-btn');
        const cancelBtn = document.getElementById('cancel-btn');

        confirmationDialog.classList.remove('hidden');
        confirmationInput.value = '';
        confirmBtn.disabled = true; // Désactiver le bouton initialement

        confirmBtn.addEventListener('click', () => {
            if (confirmationInput.value.toUpperCase() === 'SUPPRIMER') {
                supprimerUtilisateur(email);
                confirmationDialog.classList.add('hidden');
            } else {
                alert('Veuillez entrer "SUPPRIMER" pour confirmer la suppression.');
            }
        });

        confirmationInput.addEventListener('input', () => {
            // Activer le bouton uniquement si le texte est "SUPPRIMER"
            confirmBtn.disabled = confirmationInput.value.toUpperCase() !== 'SUPPRIMER';
        });

        cancelBtn.addEventListener('click', () => {
            confirmationDialog.classList.add('hidden');
        });
    });
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
    </script>
</body>
</html>
