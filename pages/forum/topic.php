<?php
require_once '../../require/config/config.php';
session_start();
require_once '../../require/sidebar/sidebar_forum.php';

if (!isset($_SESSION['utilisateur_connecte'])) {
    header('Location: ../../auth/connexion');
    exit();
}

if ($_SESSION['utilisateur_connecte']['type'] === 'banni') {
    header('Location: ../banni');
    exit();
}

$userId = $_SESSION['utilisateur_connecte']['id'];
$userQuery = $pdo->prepare("SELECT * FROM UTILISATEUR WHERE id = :id");
$userQuery->execute(['id' => $userId]);
$currentUser = $userQuery->fetch(PDO::FETCH_ASSOC);

$messagesPerPage = 25;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $messagesPerPage;

if (isset($_GET['id'])) {
    $topicId = $_GET['id'];

    $query = $pdo->prepare("SELECT * FROM TOPICS WHERE ID = :id");
    $query->execute(['id' => $topicId]);
    $topic = $query->fetch(PDO::FETCH_ASSOC);

    if (!$topic) {
        echo "Sujet non trouvé.";
        exit;
    }

    $query = $pdo->prepare("SELECT COUNT(*) FROM POSTS WHERE TOPIC_ID = :id");
    $query->execute(['id' => $topicId]);
    $totalMessages = $query->fetchColumn();
    $totalPages = ceil($totalMessages / $messagesPerPage);

    $query = $pdo->prepare("SELECT p.*, u.pseudo, u.avatar_url FROM POSTS p JOIN UTILISATEUR u ON p.USER_ID = u.id WHERE p.TOPIC_ID = :id ORDER BY p.CREATED_AT LIMIT :limit OFFSET :offset");
    $query->bindValue(':id', $topicId, PDO::PARAM_INT);
    $query->bindValue(':limit', $messagesPerPage, PDO::PARAM_INT);
    $query->bindValue(':offset', $offset, PDO::PARAM_INT);
    $query->execute();
    $posts = $query->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Sujet non spécifié.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = $_POST['content'];
    $createdAt = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare("INSERT INTO POSTS (TOPIC_ID, USER_ID, CONTENT, CREATED_AT) VALUES (:topicId, :userId, :content, :createdAt)");
    $stmt->execute(['topicId' => $topicId, 'userId' => $userId, 'content' => $content, 'createdAt' => $createdAt]);

    header("Location: topic.php?id=" . $topicId);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post_id'])) {
    $postId = $_POST['delete_post_id'];

    if ($currentUser['type'] === 'admin' || $currentUser['type'] === 'moderateur') {
        $stmt = $pdo->prepare("UPDATE POSTS SET CONTENT = 'Ce message a été supprimé par un modérateur' WHERE ID = :postId");
        $stmt->execute(['postId' => $postId]);

        header("Location: topic.php?id=" . $topicId);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_topic_id'])) {
    $topicIdToDelete = $_POST['delete_topic_id'];

    if ($currentUser['type'] === 'admin' || $currentUser['type'] === 'moderateur') {
        $stmt = $pdo->prepare("DELETE FROM POSTS WHERE TOPIC_ID = :topicId");
        $stmt->execute(['topicId' => $topicIdToDelete]);

        $stmt = $pdo->prepare("DELETE FROM TOPICS WHERE ID = :topicId");
        $stmt->execute(['topicId' => $topicIdToDelete]);

        header("Location: forum.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sujet: <?= htmlspecialchars($topic['TITLE']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/sidebar.css">
    <style>
        .post-meta {
            display: flex;
            align-items: center;
        }
        .post-meta small {
            margin-left: 1rem;
        }
        .post {
            border-bottom: 1px solid #ddd;
            padding: 1rem 0;
        }
        .post:last-child {
            border-bottom: none;
        }
        .post-header {
            display: flex;
            align-items: flex-start;
        }
        .post-header img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-right: 1rem;
            margin-top: 5px;
        }
        .post-content {
            flex-grow: 1;
        }
        .date-separator {
            text-align: center;
            margin: 2rem 0;
            color: #888;
        }
        .pagination {
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <h1 class="my-4">Sujet: <?= htmlspecialchars($topic['TITLE']) ?></h1>
        <?php if ($currentUser['type'] === 'admin' || $currentUser['type'] === 'moderateur'): ?>
            <form method="POST" class="mb-4">
                <input type="hidden" name="delete_topic_id" value="<?= $topicId ?>">
                <button type="submit" class="btn btn-danger">Supprimer le sujet</button>
            </form>
        <?php endif; ?>
        <div class="list-group mb-4">
            <?php
            $lastDate = '';
            foreach ($posts as $post):
                $postDate = date('Y-m-d', strtotime($post['CREATED_AT']));
                if ($postDate != $lastDate): ?>
                    <div class="date-separator"><?= date('d M Y', strtotime($post['CREATED_AT'])) ?></div>
                <?php endif; ?>
                <div class="list-group-item post">
                    <div class="post-header">
                        <img src="<?= htmlspecialchars($post['avatar_url']) ?>" alt="User Avatar" class="me-3">
                        <div class="post-content">
                            <div class="post-meta">
                                <strong><?= htmlspecialchars($post['pseudo']) ?></strong>
                                <small class="text-muted ms-2"><?= date('H:i', strtotime($post['CREATED_AT'])) ?></small>
                            </div>
                            <p class="mb-1"><?= htmlspecialchars($post['CONTENT']) ?></p>
                            <?php if ($currentUser['type'] === 'admin' || $currentUser['type'] === 'moderateur'): ?>
                                <form method="POST" class="mt-2">
                                    <input type="hidden" name="delete_post_id" value="<?= $post['ID'] ?>">
                                    <button type="submit" class="btn btn-warning btn-sm float-end">Supprimer</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
                $lastDate = $postDate;
            endforeach; ?>
        </div>

        <nav>
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?id=<?= $topicId ?>&page=<?= $page - 1 ?>" aria-label="Précédent">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?id=<?= $topicId ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?id=<?= $topicId ?>&page=<?= $page + 1 ?>" aria-label="Suivant">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="card">
            <div class="card-header">Ajouter un nouveau message</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="content" class="form-label">Message</label>
                        <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </div>
        </div>
        <a href="javascript:history.back()" class="btn btn-secondary mt-3">Retour</a>
    </div>
</body>
</html>
