<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['Type'] != 'admin') {
    header('Location: connexion');
    exit();
}

$stmt = $conn->prepare('SELECT Pseudo, Nom, Adresse_email, Type FROM Utilisateurs');
$stmt->execute();
$utilisateurs = $stmt->fetchAll();

$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Administration</title>
    <style>
        input[type="text"], input[type="email"], select {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
            }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .form-row input[type="text"],
        .form-row input[type="email"],
        .form-row select {
            width: 30%;
        }

        .form-row label {
            display: none;
        }

        table {
            margin: 0 auto;
            width: 40%;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:hover {background-color: #f5f5f5;}
        .hidden {
            display: none;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-gap: 10px;
            align-items: center;
        }

        .grid-container input[type="text"],
        .grid-container input[type="email"],
        .grid-container select {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
        }

        .grid-container input[type="submit"] {
            grid-column: 5 / span 2;
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .input-small {
            width: 80%;
        }

    </style>
</head>
<body>
<?php include 'header.php' ?>
    <h1>Administration</h1>

    <h2>Liste des utilisateurs</h2>
    <table>
        <thead>
            <tr>
                <th>Pseudo</th>
                <th>Nom</th>
                <th>Adresse email</th>
                <th>Type</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($utilisateurs as $utilisateur): ?>
            <tr>
                <td><input class="hidden" type="text" value="<?= htmlspecialchars($utilisateur['Pseudo']) ?>"><?= htmlspecialchars($utilisateur['Pseudo']) ?></td>
                <td><input class="hidden" type="text" value="<?= htmlspecialchars($utilisateur['Nom']) ?>"><?= htmlspecialchars($utilisateur['Nom']) ?></td>
                <td><input class="hidden" type="email" value="<?= htmlspecialchars($utilisateur['Adresse_email']) ?>"><?= htmlspecialchars($utilisateur['Adresse_email']) ?></td>
                <td><input class="hidden" type="text" value="<?= htmlspecialchars($utilisateur['Type']) ?>"><?= htmlspecialchars($utilisateur['Type']) ?></td>
                <td>
                    <button type="button" data-email="<?= htmlspecialchars($utilisateur['Adresse_email']) ?>" class="modifier-btn">Modifier</button>
                    <button type="button" class="enregistrer-btn hidden">Enregistrer</button>
                </td>
                <td>
                    <form method="post" action="supprimer_utilisateur" class="hidden">
                        <input type="hidden" name="Adresse_email" value="<?= htmlspecialchars($utilisateur['Adresse_email']) ?>">
                        <input type="submit" name="supprimer" value="Supprimer">
                    </form>
                    <button type="button" class="supprimer-btn">Supprimer</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Ajouter un utilisateur</h2>
    <form id="ajouter-utilisateur-form" method="post" action="traiter_ajout_utilisateur">
    <div class="grid-container">
        <input type="text" name="pseudo" class="input-small" required>
        <input type="text" name="nom" class="input-small" required>
        <input type="email" name="email" class="input-small" required>
        <select name="type" class="input-small" required>
            <option value="admin">Admin</option>
            <option value="moderateur">Modérateur</option>
            <option value="utilisateur">Utilisateur</option>
        </select>
        <input type="password" name="mot_de_passe" class="input-small">
        <input type="submit" name="ajouter_utilisateur" value="Ajouter l'utilisateur">
    </div>
    </form>



    <script>
    async function supprimerUtilisateur(email) {
    const response = await fetch('supprimer_utilisateur', {
        method: 'POST',
        headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `Adresse_email=${encodeURIComponent(email)}`
    });

    if (response.ok) {
        const row = document.querySelector(`tr input[type="email"][value="${email}"]`).closest('tr');
        row.remove();
    } else {
        console.error('Une erreur est survenue lors de la suppression de l\'utilisateur.');
    }
    }

    document.querySelectorAll('.modifier-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault();
        const row = e.target.closest('tr');
        const inputs = row.querySelectorAll('input:not([type="submit"]), select');
        const texts = row.querySelectorAll('td:not(:last-child)');

        inputs.forEach(input => {
        input.hidden = false;
        const previousValue = input.previousElementSibling;
        previousValue.hidden = true;
        previousValue.textContent = input.value;
        });

        row.querySelector('.modifier-btn').hidden = true;
        row.querySelector('.enregistrer-btn').hidden = false;
        row.querySelector('.supprimer-btn').hidden = true;
    });
    });

    document.querySelectorAll('.enregistrer-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault();
        const row = e.target.closest('tr');
        const inputs = row.querySelectorAll('input:not([type="submit"]), select');
        const texts = row.querySelectorAll('td:not(:last-child)');

        inputs.forEach(input => {
        input.hidden = true;
        const previousValue = input.previousElementSibling;
        previousValue.hidden = false;
        previousValue.textContent = input.value;
        });

        row.querySelector('.modifier-btn').hidden = false;
        row.querySelector('.enregistrer-btn').hidden = true;
        row.querySelector('.supprimer-btn').hidden = false;

        const updatedData = {};
        inputs.forEach(input => {
        updatedData[input.name] = input.value;
        });

        fetch('traiter_modification_utilisateur', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(updatedData)
        })
        .then(response => {
        if (response.ok) {
            console.log('Données mises à jour avec succès');
        } else {
            console.error('Échec de la mise à jour des données');
        }
        })
        .catch(error => {
        console.error('Erreur lors de la mise à jour des données :', error);
        });
    });
    });

    document.querySelectorAll('.supprimer-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault();
        const email = e.target.closest('tr').querySelector('input[type="email"]').value;
        supprimerUtilisateur(email);
    });
    });
    </script>

</body>
</html>
