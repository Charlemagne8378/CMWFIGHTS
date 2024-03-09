<?php
session_start();

if (isset($_SESSION['success_message'])) {
    echo '<script>';
    echo 'window.onload = function() {';
    echo '    document.getElementById("success-message").style.display = "block";';
    echo '}';
    echo '</script>';
    echo '<div id="success-message" style="display: none;">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Accueil</title>
    <link rel="icon" type="image/png" href="Images/cmwicon.png">    
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include'header.php' ?>
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="success-message">
        <?php echo $_SESSION['success_message']; ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>
<h1>Bienvenue sur notre site</h1>
<p>Clorem</p>
</body>
</html>
