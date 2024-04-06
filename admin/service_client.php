<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Page Vide</title>
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100%;
            z-index: 1000;
        }
    </style>
</head>

<body>
<div class="sidebar p-3">
    <div class="list-group">
        <a href="accueil" class="list-group-item list-group-item-action py-2">Accueil</a>
        <a href="billetterie" class="list-group-item list-group-item-action py-2">Billetterie</a>
        <a href="captcha" class="list-group-item list-group-item-action py-2">Captcha</a>
        <a href="classement" class="list-group-item list-group-item-action py-2">Classement</a>
        <a href="combattants" class="list-group-item list-group-item-action py-2">Combattants</a>
        <a href="evenements" class="list-group-item list-group-item-action py-2">Événements</a>
        <a href="image" class="list-group-item list-group-item-action py-2">Image</a>
        <a href="modifier_utilisateur" class="list-group-item list-group-item-action py-2">Modifier utilisateur</a>
        <a href="newsletters" class="list-group-item list-group-item-action py-2">Newsletters</a>
        <a href="service_client" class="list-group-item list-group-item-action py-2">Service client</a>
        <a href="utilisateurs" class="list-group-item list-group-item-action py-2 active">Utilisateurs</a>
    </div>
</div>
<a href="admin" class="btn btn-primary">Retourner à la page d'accueil</a>
</body>
</html>
