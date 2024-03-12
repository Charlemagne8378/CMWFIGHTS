<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-253df704d6" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Play&display=swap" rel="stylesheet">
    <title>Footer</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        .page-container {
            min-height: 100%;
            position: relative;
        }
        .content-container {
            padding-bottom: 100px; /* Hauteur du footer */
        }
        .footer {
            background: #000;
            padding: 30px 0px;
            font-family: 'Play', sans-serif;
            text-align: center;
            position: absolute;
            bottom: 0;
            width: 100%;
            box-sizing: border-box;
        }
        /* Fin de la section CSS pour le sticky footer */

        /* CSS existant pour le footer */
        .footer .row {
            width: 100%;
            margin: 1% 0%;
            padding: 0.6% 0%;
            color: gray;
            font-size: 0.8em;
        }
        .footer .row a {
            text-decoration: none;
            color: gray;
            transition: 0.5s;
        }
        .footer .row a:hover {
            color: #fff;
        }
        .footer .row ul {
            width: 100%;
        }
        .footer .row ul li {
            display: inline-block;
            margin: 0px 30px;
        }
        .footer .row a i {
            font-size: 2em;
            margin: 0% 1%;
        }
        @media (max-width: 720px) {
            .footer {
                text-align: left;
                padding: 5%;
            }
            .footer .row ul li {
                display: block;
                margin: 10px 0px;
                text-align: left;
            }
            .footer .row a i {
                margin: 0% 3%;
            }
        }
    </style>
</head>
<body>
<div class="page-container">
    <div class="content-container">
    </div>
    <footer>
        <div class="footer">
            <div class="row">
                <a href="https://www.instagram.com/cmwfight/" target="_blank"><i class="fa fa-instagram"></i></a>
                <a href="https://www.youtube.com/@CMWFIGHT" target="_blank"><i class="fa fa-youtube"></i></a>
                <a href="#"><i class="fa fa-twitter"></i></a>
            </div>
            <div class="row">
                <ul>
                    <li><a href="#">Contact us</a></li>
                    <li><a href="#">Our Services</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                </ul>
            </div>
            <div class="row">
                CMWFIGHT Copyright © 2024 cmwfight - All rights reserved || Designed By: FID75
            </div>
        </div>
    </footer>
</div>
</body>
</html>
