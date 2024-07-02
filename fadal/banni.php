<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès restreint</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            margin: 0 auto;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Accès restreint</h1>
        <div class="alert alert-danger" role="alert">
            <strong>Vous avez été banni !</strong> Vous n'avez pas accès à cette page.
        </div>
        <p class="mt-3">Pour toute question, veuillez contacter l'administrateur.</p>
        <a href="auth/logout" class="btn btn-primary mt-3">Se déconnecter</a>
    </div>
</body>
</html>
