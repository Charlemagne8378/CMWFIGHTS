<?php
require_once '../require/sidebar.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
    <title>evenement</title>
</head>
<body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const accountBtn = document.querySelector(".account-btn");
        const accountBox = document.getElementById("account-box");

        accountBtn.addEventListener("click", function () {
            console.log("Button clicked");
            accountBox.classList.toggle("show");
        });
    });
</script>

</body>
</html>
