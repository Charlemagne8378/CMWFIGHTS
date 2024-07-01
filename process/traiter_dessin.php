<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['image'])) {
    $image_data = $_POST['image'];
    $image_data = str_replace('data:image/png;base64,', '', $image_data);
    $image_data = str_replace(' ', '+', $image_data);
    $decoded_image_data = base64_decode($image_data);

    $file_name = '../Images/dessin/' . uniqid() . '.png'; // Chemin vers le dossier Images/dessin
    if (!file_exists('../Images/dessin')) {
        if (!mkdir('../Images/dessin', 0777, true)) {
            die('Echec de la création du dossier Images/dessin');
        }
    }

    if (file_put_contents($file_name, $decoded_image_data) !== false) {
        echo 'Image enregistrée avec succès : ' . $file_name;
    } else {
        echo 'Echec de l\'enregistrement de l\'image.';
    }
} else {
    echo 'Aucune donnée d\'image trouvée.';
}
?>
