<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../../require/config/config.php';

    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $title = $_POST['title'];
    $subject = $_POST['subject'];
    $action = $_POST['action'];
    $schedule = null;

    if (isset($_POST['schedule_immediate'])) {
        $schedule = date('Y-m-d H:i:s');
    } elseif (!empty($_POST['schedule'])) {
        $schedule = date('Y-m-d H:i:s', strtotime($_POST['schedule']));
    }

    if ($action == 'send') {
        if ($schedule && $schedule > date('Y-m-d H:i:s')) {
            // Envoi programmé
            $statut = 'programmé';
            if ($id) {
                $stmt = $pdo->prepare("UPDATE NEWSLETTERS SET titre = ?, sujet = ?, statut = ?, date_programmation = ? WHERE id = ?");
                $stmt->execute([$title, $subject, $statut, $schedule, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO NEWSLETTERS (titre, sujet, statut, date_programmation) VALUES (?, ?, ?, ?)");
                $stmt->execute([$title, $subject, $statut, $schedule]);
            }
            header("Location: ../../admin/newsletters");
            exit();
        } else {
            // Envoi immédiat
            $statut = 'envoyé';
            if ($id) {
                $stmt = $pdo->prepare("UPDATE NEWSLETTERS SET titre = ?, sujet = ?, statut = ?, date_envoi = NOW() WHERE id = ?");
                $stmt->execute([$title, $subject, $statut, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO NEWSLETTERS (titre, sujet, statut, date_envoi) VALUES (?, ?, ?, NOW())");
                $stmt->execute([$title, $subject, $statut]);
            }

            // Redirection vers la page de confirmation
            $newsletterId = $id ?? $pdo->lastInsertId();
            header("Location: newsletter_confirmation?id=" . $newsletterId);
            exit();
        }
    } elseif ($action == 'save') {
        $statut = 'brouillon';
        if ($id) {
            $stmt = $pdo->prepare("UPDATE NEWSLETTERS SET titre = ?, sujet = ?, statut = ?, date_creation = NOW() WHERE id = ?");
            $stmt->execute([$title, $subject, $statut, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO NEWSLETTERS (titre, sujet, statut, date_creation) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$title, $subject, $statut]);
        }

        header("Location: ../../admin/newsletters");
        exit();
    }
}
?>
