<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'Adresse_email', FILTER_VALIDATE_EMAIL);

    if ($email) {
        $stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE Adresse_email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur) {
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier l'utilisateur</title>
    <link rel="icon" type="image/png" href="Images/cmwicon.png">
</head>
<body>
    <h1>Modifier l'utilisateur</h1>
    <form method="post" action="traiter_modification">
        <input type="hidden" name="Adresse_email" value="<?= htmlspecialchars($utilisateur['Adresse_email']) ?>">
        <label for="pseudo">Pseudo :</label>
        <input type="text" name="pseudo" value="<?= htmlspecialchars($utilisateur['Pseudo']) ?>" required><br>
        <label for="nom">Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($utilisateur['Nom']) ?>" required><br>
        <label for="email">Adresse email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($utilisateur['Adresse_email']) ?>" required><br>
        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" name="mot_de_passe"><br>
        <label for="type">Type :</label>
        <select name="type" required>
            <option value="admin" <?= ($utilisateur['Type'] === 'admin') ? 'selected' : '' ?>>Admin</option>
            <option value="moderateur" <?= ($utilisateur['Type'] === 'moderateur') ? 'selected' : '' ?>>Modérateur</option>
            <option value="utilisateur" <?= ($utilisateur['Type'] === 'utilisateur') ? 'selected' : '' ?>>Utilisateur</option>
        </select><br>
        <input type="submit" name="modifier_utilisateur" value="Enregistrer les modifications">
    </form>
</body>
</html>

<?php
        } else {
            echo "Aucun utilisateur trouvé avec cette adresse e-mail.";
        }
    } else {
        echo "Adresse e-mail invalide.";
    }
}

$conn = null;
?>
