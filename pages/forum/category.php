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

if (isset($_GET['id'])) {
    $categoryId = $_GET['id'];

    $queryCategory = $pdo->prepare("SELECT * FROM FORUM_CATEGORIES WHERE ID = :id");
    $queryCategory->execute(['id' => $categoryId]);
    $category = $queryCategory->fetch(PDO::FETCH_ASSOC);

    $canDeleteCategory = ($currentUser['type'] === 'admin' || $currentUser['type'] === 'moderateur');

    if ($canDeleteCategory && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category_id'])) {
        $categoryIdToDelete = $_POST['delete_category_id'];

        $pdo->beginTransaction();
        try {
            $stmtDeleteTopics = $pdo->prepare("DELETE FROM TOPICS WHERE CATEGORY_ID = :categoryId");
            $stmtDeleteTopics->execute(['categoryId' => $categoryIdToDelete]);

            $stmtDeletePosts = $pdo->prepare("DELETE FROM POSTS WHERE TOPIC_ID IN (SELECT ID FROM TOPICS WHERE CATEGORY_ID = :categoryId)");
            $stmtDeletePosts->execute(['categoryId' => $categoryIdToDelete]);

            $stmtDeleteCategory = $pdo->prepare("DELETE FROM FORUM_CATEGORIES WHERE ID = :categoryId");
            $stmtDeleteCategory->execute(['categoryId' => $categoryIdToDelete]);

            $pdo->commit();
            header("Location: forum.php");
            exit;
        } catch (Exception $e) {
            $pdo->rollback();
            echo "Erreur lors de la suppression de la catégorie: " . $e->getMessage();
        }
    }

    $queryTopics = $pdo->prepare("SELECT * FROM TOPICS WHERE CATEGORY_ID = :categoryId");
    $queryTopics->execute(['categoryId' => $categoryId]);
    $topics = $queryTopics->fetchAll(PDO::FETCH_ASSOC);

    if ($currentUser['type'] === 'admin' || $currentUser['type'] === 'moderateur') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_topic_id'])) {
            $topicIdToDelete = $_POST['delete_topic_id'];

            try {
                $pdo->beginTransaction();

                $stmtDeletePosts = $pdo->prepare("DELETE FROM POSTS WHERE TOPIC_ID = :topicId");
                $stmtDeletePosts->execute(['topicId' => $topicIdToDelete]);

                $stmtDeleteTopic = $pdo->prepare("DELETE FROM TOPICS WHERE ID = :topicId");
                $stmtDeleteTopic->execute(['topicId' => $topicIdToDelete]);

                $pdo->commit();
                header("Location: category.php?id=$categoryId");
                exit;
            } catch (Exception $e) {
                $pdo->rollback();
                echo "Erreur lors de la suppression du sujet: " . $e->getMessage();
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_topic_title'])) {
        $newTopicTitle = $_POST['new_topic_title'];

        try {
            $stmtInsertTopic = $pdo->prepare("INSERT INTO TOPICS (TITLE, CATEGORY_ID, USER_ID) VALUES (:title, :categoryId, :userId)");
            $stmtInsertTopic->execute(['title' => $newTopicTitle, 'categoryId' => $categoryId, 'userId' => $userId]);

            header("Location: category.php?id=$categoryId");
            exit;
        } catch (Exception $e) {
            echo "Erreur lors de la création du sujet: " . $e->getMessage();
        }
    }
} else {
    echo "Catégorie non spécifiée.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catégorie: <?= htmlspecialchars($category['NAME']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/sidebar.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Catégorie: <?= htmlspecialchars($category['NAME']) ?></h1>
        <p><?= htmlspecialchars($category['DESCRIPTION']) ?></p>

        <?php if ($canDeleteCategory): ?>
            <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie? Tous les sujets associés seront également supprimés.');">
                <input type="hidden" name="delete_category_id" value="<?= $category['ID'] ?>">
                <button type="submit" class="btn btn-danger">Supprimer la catégorie</button>
            </form>
        <?php endif; ?>

        <div class="list-group mb-4">
            <?php foreach ($topics as $topic): ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="topic.php?id=<?= $topic['ID'] ?>" class="list-group-item-action">
                            <?= htmlspecialchars($topic['TITLE']) ?>
                        </a>
                        <?php if ($currentUser['type'] === 'admin' || $currentUser['type'] === 'moderateur'): ?>
                            <form method="POST" class="mb-0">
                                <input type="hidden" name="delete_topic_id" value="<?= $topic['ID'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="card">
            <div class="card-header">Ajouter un nouveau sujet</div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="new_topic_title">Titre du sujet</label>
                        <input type="text" class="form-control" id="new_topic_title" name="new_topic_title" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
