<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permissions Utilisateurs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
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
    <a class="nav-link" href="utilisateurs">
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
    <a class="nav-link active" href="permissions">
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

<div class="main-content">
    <div class="container mt-5">
        <h1>Gestion des Permissions</h1>
        <?php
        require_once '../config/config.php';

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

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="http://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
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
