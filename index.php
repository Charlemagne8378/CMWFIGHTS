<!DOCTYPE html>
<html>
    <head>
        <title>Acceuil</title>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" sizes="64x64" href="Images/cmwicon.png">
        <style>
            /* Styles généraux */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

h2 {
    font-size: 24px;
    margin-bottom: 10px;
}

/* Styles de la section des actualités */
.news {
    margin-top: 200px;
    background-color: #f9f9f9;
    padding: 20px;
}

.news-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.news-item {
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.news-item img {
    max-width: 100%;
    border-radius: 5px;
}

.news-item h3 {
    font-size: 18px;
    margin-top: 10px;
    margin-bottom: 5px;
}

.news-item p {
    font-size: 14px;
    color: #666;
}

/* Styles de la section des sponsors */
.sponsors {
    padding: 20px;
}

.sponsors-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.sponsor-item img {
    max-width: 150px;
    max-height: 100px;
    border-radius: 5px;
}

        </style>
    </head>

    <body>
        <?php include'header.php' ?>

      <!-- Section News -->
    <section class="news">
        <h2>Les dernières actualités</h2>
        <div class="news-container" id="newsContainer">
            <!-- Contenu des actualités chargé dynamiquement par JavaScript -->
        </div>
    </section>

    <!-- Section Sponsors -->
    <section class="sponsors">
        <h2>Nos sponsors</h2>
        <div class="sponsors-container" id="sponsorsContainer">
            <!-- Contenu des sponsors chargé dynamiquement par JavaScript -->
        </div>
    </section>

    
        <?php include'footer.php' ?>
        <script>
            // Fonction pour charger les actualités depuis le backend et les afficher sur la page
function loadNews() {
    fetch('adminacceuil.php?action=get_news')
    .then(response => response.json())
    .then(data => {
        const newsContainer = document.getElementById('newsContainer');
        newsContainer.innerHTML = ''; // Effacer le contenu précédent
        data.forEach(news => {
            const newsItem = document.createElement('div');
            newsItem.classList.add('news-item');
            newsItem.innerHTML = `
                <img src="${news.image}" alt="${news.title}">
                <h3>${news.title}</h3>
                <p>${news.text}</p>
            `;
            newsContainer.appendChild(newsItem);
        });
    })
    .catch(error => console.error('Erreur lors du chargement des actualités :', error));
}

// Fonction pour charger les sponsors depuis le backend et les afficher sur la page
function loadSponsors() {
    fetch('adminacceuil.php?action=get_sponsors')
    .then(response => response.json())
    .then(data => {
        const sponsorsContainer = document.getElementById('sponsorsContainer');
        sponsorsContainer.innerHTML = ''; // Effacer le contenu précédent
        data.forEach(sponsor => {
            const sponsorItem = document.createElement('div');
            sponsorItem.classList.add('sponsor-item');
            sponsorItem.innerHTML = `
                <img src="${sponsor.logo}" alt="${sponsor.name}">
            `;
            sponsorsContainer.appendChild(sponsorItem);
        });
    })
    .catch(error => console.error('Erreur lors du chargement des sponsors :', error));
}

// Appeler les fonctions pour charger les actualités et les sponsors au chargement de la page
window.onload = function() {
    loadNews();
    loadSponsors();
};

        </script>
    </body>
</html>
