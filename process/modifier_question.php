<?php
require_once '../config/config.php';

if (!isset($_POST['question_id']) || !is_numeric($_POST['question_id'])) {
    header("Location: ../index.php"); 
    exit();
}

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $question_id = $_POST['question_id'];
    $question = htmlspecialchars(strip_tags(trim($_POST["question"])));
    $answer = htmlspecialchars(strip_tags(trim($_POST["answer"])));

    $sql = "UPDATE captcha SET question = ?, answer = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt) {
        $stmt->execute([$question, $answer, $question_id]);
    }
} catch (PDOException $e) {
} finally {
    $pdo = null;
}

header("Location: ../admin/captcha");
exit();
?>
