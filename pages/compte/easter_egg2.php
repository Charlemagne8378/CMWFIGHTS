<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../Images/cmwicon.png">
    <title>Delon</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .background-image {
            height: 100%;
            width: 100%;
            background-image: url('../../Images/kevin.png');
            background-repeat: repeat;
        }

        @keyframes rotateAndChangeColor {
            0% {
                transform: rotate(0deg);
                color: red;
            }
            25% {
                color: blue;
            }
            50% {
                transform: rotate(180deg);
                color: green;
            }
            75% {
                color: yellow;
            }
            100% {
                transform: rotate(360deg);
                color: red;
            }
        }

        .alter-table-text {
            position: absolute;
            font-size: 100px;
            font-weight: bold;
            animation: rotateAndChangeColor 5s infinite;
        }

        .text1 {
            top: 25%;
            left: 25%;
            transform: translate(-50%, -50%);
        }

        .text2 {
            top: 25%;
            left: 75%;
            transform: translate(-50%, -50%);
        }

        .text3 {
            top: 75%;
            left: 25%;
            transform: translate(-50%, -50%);
        }

        .text4 {
            top: 75%;
            left: 75%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
    <div class="background-image"></div>
    <div class="alter-table-text text1">LES MATHS EN C</div>
    <div class="alter-table-text text2">LES MATHS EN C</div>
    <div class="alter-table-text text3">LES MATHS EN C</div>
    <div class="alter-table-text text4">LES MATHS EN C</div>
</body>
</html>
