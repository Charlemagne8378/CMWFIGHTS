<?php
require_once '../../require/config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer les événements existants avec leurs combats associés
$sql = "SELECT E.evenement_id, E.date, E.lieu, E.heure, 
               C.combat_id, C1.nom AS combattant1_nom, C1.image_url AS combattant1_photo, C1.palmares_boxe AS combattant1_palmares_boxe, C1.palmares_mma AS combattant1_palmares_mma,
               C2.nom AS combattant2_nom, C2.image_url AS combattant2_photo, C2.palmares_boxe AS combattant2_palmares_boxe, C2.palmares_mma AS combattant2_palmares_mma,
               C.statut, CAT.category_name AS categorie_nom, D.discipline_name AS discipline_nom
        FROM EVENEMENT E
        LEFT JOIN COMBAT C ON E.evenement_id = C.evenement_id
        LEFT JOIN COMBATTANT C1 ON C.combattant1_id = C1.combattant_id
        LEFT JOIN COMBATTANT C2 ON C.combattant2_id = C2.combattant_id
        LEFT JOIN CATEGORIES CAT ON C.category_id = CAT.category_id
        LEFT JOIN DISCIPLINE D ON C.discipline_id = D.discipline_id
        ORDER BY E.date, E.heure";

$stmt = $pdo->query($sql);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Grouper les combats par événement
$groupedEvents = [];
foreach ($events as $event) {
    $groupedEvents[$event['evenement_id']]['details'] = [
        'date' => $event['date'],
        'lieu' => $event['lieu'],
        'heure' => $event['heure']
    ];
    $groupedEvents[$event['evenement_id']]['combats'][] = [
        'combat_id' => $event['combat_id'],
        'combattant1_nom' => $event['combattant1_nom'],
        'combattant1_photo' => $event['combattant1_photo'],
        'combattant1_palmares_boxe' => $event['combattant1_palmares_boxe'],
        'combattant1_palmares_mma' => $event['combattant1_palmares_mma'],
        'combattant2_nom' => $event['combattant2_nom'],
        'combattant2_photo' => $event['combattant2_photo'],
        'combattant2_palmares_boxe' => $event['combattant2_palmares_boxe'],
        'combattant2_palmares_mma' => $event['combattant2_palmares_mma'],
        'statut' => $event['statut'],
        'categorie_nom' => $event['categorie_nom'],
        'discipline_nom' => $event['discipline_nom']
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Événements CMW</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
        }
        .event-card {
            background-color: #1e1e1e;
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
            padding: 1rem;
        }
        .combat-details {
            display: none;
            background-color: #2a2a2a;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
        }
        .combat-details img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 0.5rem;
        }
        .toggle-details {
            cursor: pointer;
            color: #00aaff;
        }
        .toggle-details:hover {
            text-decoration: underline;
        }
        .combattant-nom {
            font-weight: bold;
            font-size: 1.2rem;
        }
        h2 {
            text-align: center;
        }
        .my-4{
            margin-top : 10rem !important;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <?php include '../../header.php' ?>
    <div class="container">
        <h2 class="my-4">Événements CMW</h2>
        <?php foreach ($groupedEvents as $evenement_id => $event): ?>
            <div class="event-card">
                <h3>CMW n°<?php echo $evenement_id; ?></h3>
                <p>Date: <?php echo $event['details']['date']; ?></p>
                <p>Lieu: <?php echo $event['details']['lieu']; ?></p>
                <p>Heure: <?php echo $event['details']['heure']; ?></p>
                <p class="toggle-details" data-event="<?php echo $evenement_id; ?>">Voir la carte complète</p>
                <div class="combat-details" id="details-<?php echo $evenement_id; ?>">
                    <?php if (isset($event['combats'])): ?>
                        <?php foreach ($event['combats'] as $combat): ?>
                            <div class="combat-card">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <img src="<?php echo $combat['combattant1_photo']; ?>" alt="Photo de <?php echo $combat['combattant1_nom']; ?>">
                                        <p class="combattant-nom"><?php echo $combat['combattant1_nom']; ?></p>
                                        <p>Boxe palmarès: <?php echo $combat['combattant1_palmares_boxe']; ?></p>
                                        <p>MMA palmarès: <?php echo $combat['combattant1_palmares_mma']; ?></p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <p><?php echo $combat['statut']; ?></p>
                                        <p><?php echo $combat['categorie_nom']; ?></p>
                                        <p><?php echo $combat['discipline_nom']; ?></p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <img src="<?php echo $combat['combattant2_photo']; ?>" alt="Photo de <?php echo $combat['combattant2_nom']; ?>">
                                        <p class="combattant-nom"><?php echo $combat['combattant2_nom']; ?></p>
                                        <p>Boxe palmarès: <?php echo $combat['combattant2_palmares_boxe']; ?></p>
                                        <p>MMA palmarès: <?php echo $combat['combattant2_palmares_mma']; ?></p>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucun combat pour cet événement.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        $(document).ready(function(){
            $(".toggle-details").click(function(){
                var eventId = $(this).data("event");
                $("#details-" + eventId).toggle();
            });
        });
    </script>
</body>
</html>
