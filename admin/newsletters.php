<?php
require_once '../config/config.php';
require_once '../function/function.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$stmt = $pdo->query("SELECT * FROM UTILISATEUR WHERE newsletter = 1");
$users = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM NEWSLETTER ORDER BY id DESC");
$newsletters = $stmt->fetchAll();

if (isset($_POST['delete_newsletter'])) {
    $newsletter_id = $_POST['delete_id'];

    $query = "DELETE FROM NEWSLETTER WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $newsletter_id);
    $stmt->execute();

    header('Location: newsletters');
    exit();    
}

if (isset($_POST['send_newsletter'])) {
    $subject = $_POST['subject'];
    $content = $_POST['content'];
    $sent_to = implode(',', array_column($users, 'id'));

    $stmt = $pdo->prepare("INSERT INTO NEWSLETTER (sujet, contenu, date_envoi, envoye_a) VALUES (:subject, :content, NOW(), :sent_to)");
    $stmt->bindParam(':subject', $subject);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':sent_to', $sent_to);
    $stmt->execute();

    foreach ($users as $user) {
        sendEmail($user['adresse_email'], $subject, $content);
    }

    header('Location: newsletters');
    exit();
}

if (isset($_GET['unsubscribe_email'])) {
    $email = $_GET['unsubscribe_email'];

    $query = "UPDATE UTILISATEUR SET newsletter = 0 WHERE adresse_email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    echo "<p>Vous avez été désabonné de notre newsletter avec succès.</p>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration de la newsletter</title>
    <link rel="icon" type="image/png" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
        <a class="nav-link active" href="newsletters">
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
    <div class="container mt-5">
        <h1>Administration de la newsletter</h1>
        <p>Nombre total d'abonnés : <?php echo count($users); ?></p>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pseudo</th>
                    <th>Adresse e-mail</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['pseudo']); ?></td>
                        <td><?php echo htmlspecialchars($user['adresse_email']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <hr>
        <h2>Créer une nouvelle newsletter</h2>
        <form action="newsletters" method="post">
            <div class="form-group">
                <label for="subject">Sujet</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="content">Contenu</label>
                <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
            </div>
            <button type="submit" name="send_newsletter" class="btn btn-primary">Envoyer la newsletter</button>
        </form>
        <hr>
        <h2>Historique des newsletters</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sujet</th>
                    <th>Envoyé à</th>
                    <th>Date d'envoi</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newsletters as $newsletter) : ?>
                    <tr>
                        <td><?php echo $newsletter['id']; ?></td>
                        <td><?php echo htmlspecialchars($newsletter['sujet']); ?></td>
                        <td><?php echo htmlspecialchars($newsletter['envoye_a']); ?></td>
                      s  <td><?php echo htmlspecialchars($newsletter['date_envoi']); ?></td>
                        <td>
                            <form action="newsletters" method="post">
                                <input type="hidden" name="delete_id" value="<?php echo $newsletter['id']; ?>">
                                <button type="submit" name="delete_newsletter" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
    </script>
</body>
</html>
