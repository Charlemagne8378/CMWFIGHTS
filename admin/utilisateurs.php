<?php
require_once '../require/config/config.php';
require_once '../require/sidebar/sidebar.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

$perPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare('SELECT COUNT(*) FROM UTILISATEUR WHERE type != "invite"');
$stmt->execute();
$totalUsers = $stmt->fetchColumn();
$totalPages = ceil($totalUsers / $perPage);

$stmt = $pdo->prepare('SELECT pseudo, nom, adresse_email, type, derniere_connexion FROM UTILISATEUR LIMIT :offset, :perpage');
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':perpage', $perPage, PDO::PARAM_INT);
$stmt->execute();
$utilisateurs = $stmt->fetchAll();

$pdo = null;

function adjustTime($datetime) {
    if ($datetime) {
        $date = new DateTime($datetime);
        $date->modify('+2 hours');
        return $date->format('Y-m-d H:i:s');
    }
    return '';
}
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Administration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
    <link rel="stylesheet" href="../style/utilisateurs.css">
</head>
<body>
<div class="container mt-4">
    <div class="row">
        <div class="col-12 mx-auto">
            <h1 class="mb-4">Administration</h1>

            <h2>Statistiques</h2>
            <p>Nombre total d'utilisateurs inscrits : <?= htmlspecialchars($totalUsers) ?></p>

            <h2 class="mb-3">Liste des utilisateurs</h2>
            <div class="table-responsive">
                <table class="table table-striped" id="utilisateurs-table">
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
                            <td><?= htmlspecialchars(adjustTime($utilisateur['derniere_connexion'] ?? '')) ?></td>
                            <td class="text-center actions-column">
                                <?php if ($utilisateur['type'] !== 'admin') : ?>
                                    <a href="modifier_utilisateur.php?pseudo=<?= urlencode($utilisateur['pseudo']); ?>" class="btn btn-primary btn-sm">Modifier</a>
                                    <?php if ($utilisateur['type'] !== 'banni') : ?>
                                        <button type="button" class="btn btn-danger btn-sm supprimer-btn" data-email="<?= htmlspecialchars($utilisateur['adresse_email']) ?>">Supprimer</button>
                                        <button type="button" class="btn btn-warning btn-sm ban-btn" data-email="<?= htmlspecialchars($utilisateur['adresse_email']) ?>">Ban</button>
                                    <?php else : ?>
                                        <button type="button" class="btn btn-danger btn-sm supprimer-btn btn-disabled">Supprimer</button>
                                        <button type="button" class="btn btn-warning btn-sm ban-btn btn-disabled">Banni</button>
                                    <?php endif; ?>
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
                <form id="ajouter-utilisateur-form" method="post" action="../process/utilisateur/ajout_utilisateur.php">
                    <div class="mb-3">
                        <label for="pseudo" class="form-label">Pseudo</label>
                        <input type="text" class="form-control" id="pseudo" name="pseudo" required>
                    </div>
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="adresse_email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" id="adresse_email" name="adresse_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="mot_de_passe" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmation_mot_de_passe" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" id="confirmation_mot_de_passe" name="confirmation_mot_de_passe" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="utilisateur">Utilisateur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>

            <nav aria-label="Pagination" class="mt-4">
                <ul class="pagination justify-content-center" id="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<div id="confirmation-dialog" class="confirmation-dialog hidden">
    <div class="dialog-content">
        <p class="instruction-text"></p>
        <input type="text" id="confirmation-input" class="form-control" placeholder="Entrez ici" autocomplete="off">
        <div class="dialog-buttons">
            <button type="button" class="btn btn-primary" id="confirm-btn">Confirmer</button>
            <button type="button" class="btn btn-secondary" id="cancel-btn">Annuler</button>
        </div>
    </div>
</div>

<script src="../scripts/utilisateurs.js"></script>
<script src="../scripts/compte.js"></script>
</body>
</html>

