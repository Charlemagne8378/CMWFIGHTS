<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cookies</title>
    <style>
        .cookie-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: #333;
    color: #fff;
    padding: 10px;
    text-align: center;
}

.accept-cookies-btn {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

    </style>
</head>
<body>
    <div id="cookieBanner" class="cookie-banner">
        <p>Nous utilisons des cookies pour améliorer votre expérience. En continuant à utiliser ce site, vous acceptez notre utilisation des cookies.</p>
        <button id="acceptCookiesBtn" class="accept-cookies-btn">Accepter</button>
    </div>
    
    
    <script>
function hideCookieBanner() {
    document.getElementById('cookieBanner').style.display = 'none';
    // Enregistrer un cookie pour se souvenir que l'utilisateur a accepté les cookies
    document.cookie = 'cookiesAccepted=true; expires=Fri, 31 Dec 9999 23:59:59 GMT';
}

// Vérifier si l'utilisateur a déjà accepté les cookies
function checkCookiesAccepted() {
    if (document.cookie.includes('cookiesAccepted=true')) {
        hideCookieBanner();
    }
}


document.getElementById('acceptCookiesBtn').addEventListener('click', hideCookieBanner);

// Vérifier si les cookies ont déjà été acceptés lorsque la page se charge
document.addEventListener('DOMContentLoaded', checkCookiesAccepted);

    </script>
</body>
</html>