<?php
require_once '../require/sidebar.php';
require_once '../config/config.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permissions Utilisateurs</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
</head>
<body>

<div class="main-content">
    <div class="container mt-5">
        <h1>Gestion des Permissions</h1>
        <?php

        // Connexion à la base de données
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connexion échouée: " . $conn->connect_error);
        }

        // Ajouter une nouvelle permission
        if (isset($_POST['add_permission'])) {
            $type_utilisateur = $_POST['type_utilisateur'];
            $permission = $_POST['permission'];
            $sql = "INSERT INTO PERMISSIONS (type_utilisateur, permission) VALUES ('$type_utilisateur', '$permission')";
            $conn->query($sql);
        }

        // Modifier une permission existante
        if (isset($_POST['update_permission'])) {
            $id = $_POST['id'];
            $type_utilisateur = $_POST['type_utilisateur'];
            $permission = $_POST['permission'];
            $sql = "UPDATE PERMISSIONS SET type_utilisateur='$type_utilisateur', permission='$permission' WHERE id=$id";
            $conn->query($sql);
        }

        // Supprimer une permission
        if (isset($_POST['delete_permission'])) {
            $id = $_POST['id'];
            $sql = "DELETE FROM PERMISSIONS WHERE id=$id";
            $conn->query($sql);
        }

        // Récupérer toutes les permissions
        $result = $conn->query("SELECT * FROM PERMISSIONS");
        ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type d'utilisateur</th>
                    <th>Permission</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['type_utilisateur']; ?></td>
                        <td><?php echo $row['permission']; ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editPermission(<?php echo $row['id']; ?>, '<?php echo $row['type_utilisateur']; ?>', '<?php echo $row['permission']; ?>')">Modifier</button>
                            <form method="POST" action="" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_permission" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2 class="mt-5">Ajouter une nouvelle permission</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="type_utilisateur">Type d'utilisateur</label>
                <select name="type_utilisateur" id="type_utilisateur" class="form-control">
                    <option value="utilisateur">Utilisateur</option>
                    <option value="admin">Admin</option>
                    <option value="moderateur">Modérateur</option>
                </select>
            </div>
            <div class="form-group">
                <label for="permission">Permission</label>
                <input type="text" name="permission" id="permission" class="form-control" required>
            </div>
            <button type="submit" name="add_permission" class="btn btn-primary">Ajouter</button>
        </form>

        <div id="editPermissionModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier la permission</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <input type="hidden" name="id" id="edit_id">
                            <div class="form-group">
                                <label for="edit_type_utilisateur">Type d'utilisateur</label>
                                <select name="type_utilisateur" id="edit_type_utilisateur" class="form-control">
                                    <option value="utilisateur">Utilisateur</option>
                                    <option value="admin">Admin</option>
                                    <option value="moderateur">Modérateur</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_permission">Permission</label>
                                <input type="text" name="permission" id="edit_permission" class="form-control" required>
                            </div>
                            <button type="submit" name="update_permission" class="btn btn-primary">Modifier</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('.account-btn').click(function() {
            $('.account-box').toggleClass('show');
        });
    });

    function editPermission(id, type_utilisateur, permission) {
        $('#edit_id').val(id);
        $('#edit_type_utilisateur').val(type_utilisateur);
        $('#edit_permission').val(permission);
        $('#editPermissionModal').modal('show');
    }
</script>

</body>
</html>
