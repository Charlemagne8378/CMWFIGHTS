<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../../require/config/config.php';

    $title = $_POST['title'];
    $subject = $_POST['subject'];
    $action = $_POST['action'];

    if (isset($_POST['schedule_immediate'])) {
        $schedule = date('Y-m-d H:i:s'); 
    } else {
        $schedule = $_POST['schedule'];
    }
    if ($action == 'send') {
        $stmt = $pdo->prepare("INSERT INTO NEWSLETTERS (titre, sujet, statut, date_envoi) VALUES (?, ?, 'envoyé', ?)");
        $stmt->execute([$title, $subject, $schedule]);
        header("Location: ../admin/newsletter_confirmation.php");
        exit();
    } elseif ($action == 'save') {
        $stmt = $pdo->prepare("INSERT INTO NEWSLETTERS (titre, sujet, statut, date_creation) VALUES (?, ?, 'brouillon', NOW())");
        $stmt->execute([$title, $subject]);
        header("Location: ../../admin/newsletters");
        exit();
    }
}
?>
