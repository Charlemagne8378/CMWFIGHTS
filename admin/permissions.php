<?php
include '../require/config/config.php';
include '../require/function/function.php';
session_start();
require_once '../require/sidebar/sidebar.php';

// Récupérer les rôles et permissions
$roles = getRoles();
$permissions = getPermissions();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_role'])) {
        $nomRole = $_POST['nom_role'];
        ajouterRole($nomRole);
        header('Location: permissions');
        exit();
    } elseif (isset($_POST['update_permissions'])) {
        $roleId = $_POST['role_id'];
        $selectedPermissions = $_POST['permissions'] ?? [];
        associerRolePermissions($roleId, $selectedPermissions);
        header('Location: permissions');
        exit();
    } elseif (isset($_POST['delete_role'])) {
        $roleId = $_POST['role_id'];
        supprimerRole($roleId, $pdo);
        header('Location: permissions');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Rôles et Permissions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../style/sidebar.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Gestion des Rôles et Permissions</h1>

        <div class="card mt-4">
            <div class="card-header">
                Ajouter un nouveau rôle
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="nom_role">Nom du rôle :</label>
                        <input type="text" id="nom_role" name="nom_role" class="form-control" required>
                    </div>
                    <button type="submit" name="add_role" class="btn btn-primary mt-2">Ajouter</button>
                </form>
            </div>
        </div>

        <div class="mt-4">
            <?php foreach ($roles as $role): ?>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><?= htmlspecialchars($role['NOM_ROLE']) ?></span>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="role_id" value="<?= $role['ID'] ?>">
                            <button type="submit" name="delete_role" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?')">Supprimer</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="role_id" value="<?= $role['ID'] ?>">
                            <?php foreach ($permissions as $permission): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="<?= $permission['ID'] ?>"
                                        <?= roleHasPermission($role['ID'], $permission['ID']) ? 'checked' : '' ?>>
                                    <label class="form-check-label">
                                        <?= htmlspecialchars($permission['NOM_PERMISSION']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            <button type="submit" name="update_permissions" class="btn btn-primary mt-2">Mettre à jour les permissions</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
