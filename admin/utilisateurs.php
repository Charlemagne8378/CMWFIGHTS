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
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
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

            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mt-4">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item<?= $i == $page ? ' active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>

            <div id="confirmation-dialog" class="hidden">
                <p><input type="text" class="w-50 form-control" id="confirmation-input"></p>
                <p class="instruction-text"></p>
                <button type="button" id="confirm-btn" class="btn btn-danger btn-sm">Confirmer</button>
                <button type="button" id="cancel-btn" class="btn btn-secondary btn-sm">Annuler</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../scripts/utilisateurs.js"></script>
<script src="../scripts/compte.js"></script>
</body>
</html>
