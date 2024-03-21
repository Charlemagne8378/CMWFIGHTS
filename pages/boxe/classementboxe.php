<!DOCTYPE html>
<html>
    <head>
        <title>Classement</title>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" sizes="64x64" href="/Images/cmwicon.png">
        <style>
            body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #242222;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    margin-left: 200px;
}

.category {
    margin-bottom: 20px;
    display: inline-block;
    width: calc(33.33% - 100px);
    vertical-align: top;
    margin-right: 50px;
}

.category:nth-child(3n){
    margin-right: 0;
}

.category-title {
    font-size: 24px;
    font-weight: bold;
    color: #f3f2f2;
    margin-bottom: 10px;
}

.champion {
    display: flex;
    flex-direction: column;
    margin-bottom: 10px;
}

.champion-image {
    width: 300px;
    height: 200px;
    margin-bottom: 10px;
}

.champion-name {
    font-size: 20px;
    color: rgb(0, 0, 0);
    background: gold;
    padding: 10px;
    border-radius: 10px;
    width: 100%;
    font-weight: bold;
}

.fighters {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between; /* Répartir les combattants dans l'espace disponible */
    flex-direction: column;
}

.fighter {
    width: 100%;
    margin-bottom: 5px;
    background-color: #f1f5f1;
    padding: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
}

.fighter-name {
    font-size: 18px;
    font-weight: bold;
    color: #333;
}

.fighter-record {
    font-size: 14px;
    color: #666;
    margin-left: 15px;
}

.rank {
    margin-right: 10px;
    font-weight: bold;
}

h1{
    color: #f1f5f1;
    text-align: center;
    margin-bottom: 10px;
    margin-top: 10px;
}

.separator{
    height: 10px;
    background-color: #ffffff;
    margin: 20px;
    margin: 50px;
}
        </style>
    </head>

    <body>

    <?php include'../../header.php' ?>
      
      <div class="separator"></div>
      <h1>Classement Boxe Anglaise</h1>
      <div class="separator"></div>
      

        <div class="container">
            <div class="category">
              <div class="category-title">Poids Plumes(-65kg)</div>
              <div class="champion">
                <img class="champion-image" src="/Images/alias.png" alt="Champion Boxe">
                <div class="champion-name">#C Alias 1-0-0</div>
              </div>
              <div class="fighters">
               
                <div class="fighter">
                  <div class="rank">#1</div>  
                  <div class="fighter-name">Fabio</div>
                  <div class="fighter-record">1-0-0</div>
                </div>
                
                  <div class="fighter">
                    <div class="rank">#2</div>
                    <div class="fighter-name">Belaid</div>
                    <div class="fighter-record">1-1-0</div>
                  </div>

                  <div class="fighter">
                    <div class="rank">#3</div>
                    <div class="fighter-name">Ilyas</div>
                    <div class="fighter-record">0-1-0</div>
                  </div>

                  <div class="fighter">
                    <div class="rank">#4</div>
                    <div class="fighter-name">Mohamed Ali</div>
                    <div class="fighter-record">0-0-0</div>
                  </div>

                  <div class="fighter">
                    <div class="rank">#5</div>
                    <div class="fighter-name">Jawad</div>
                    <div class="fighter-record">0-0-0</div>
                  </div>
              </div>
            </div>


            <div class="category">
                <div class="category-title">Poids Légers(-70kg)</div>
                <div class="champion">
                  <img class="champion-image" src="/Images/randomwhite.png" alt="Champion Boxe">
                  <div class="champion-name">#C NA</div>
                </div>
                <div class="fighters">
                 
                  <div class="fighter">
                    <div class="rank">#1</div>  
                    <div class="fighter-name">Yannick</div>
                    <div class="fighter-record">0-0-0</div>
                  </div>
                  
                    <div class="fighter">
                      <div class="rank">#2</div>
                      <div class="fighter-name">Aslan</div>
                      <div class="fighter-record">0-0-0</div>
                    </div>
                </div>
              </div>
          
              <div class="category">
                <div class="category-title">Poids Mi-Moyens(-75kg)</div>
                <div class="champion">
                  <img class="champion-image" src="/Images/fidchamp.png" alt="Champion Boxe">
                 <div class="champion-name">#C FID 1-0-0</div>
                </div>
                <div class="fighters">
                 
                  <div class="fighter">
                    <div class="rank">#1</div>  
                    <div class="fighter-name">Basile</div>
                    <div class="fighter-record">1-0-0</div>
                  </div>
                  
                    <div class="fighter">
                      <div class="rank">#2</div>
                      <div class="fighter-name">Irs0</div>
                      <div class="fighter-record">0-1-0</div>
                    </div>
  
                    <div class="fighter">
                      <div class="rank">#3</div>
                      <div class="fighter-name">S2R</div>
                      <div class="fighter-record">0-1-0</div>
                    </div>
  
                    <div class="fighter">
                      <div class="rank">#4</div>
                      <div class="fighter-name">Comoco</div>
                      <div class="fighter-record">0-0-0</div>
                    </div>
  
                    <div class="fighter">
                      <div class="rank">#5</div>
                      <div class="fighter-name">Lanzoo</div>
                      <div class="fighter-record">0-0-0</div>
                    </div>

                    <div class="fighter">
                      <div class="rank">#6</div>
                      <div class="fighter-name">NANO</div>
                      <div class="fighter-record">0-0-0</div>
                    </div>

                    <div class="fighter">
                      <div class="rank">#7</div>
                      <div class="fighter-name">Ehdgi Azor</div>
                      <div class="fighter-record">0-0-0</div>
                    </div>

                </div>
              </div>

              <div class="category">
                <div class="category-title">Poids Super-Moyen(-85kg)</div>
                <div class="champion">
                  <img class="champion-image" src="/Images/randomwhite.png" alt="Champion Boxe">
                  <div class="champion-name">#C NA</div>
                </div>
                <div class="fighters">
                 
                  <div class="fighter">
                    <div class="rank">#1</div>  
                    <div class="fighter-name">Fadal</div>
                    <div class="fighter-record">0-0-0</div>
                  </div>
                  
                    <div class="fighter">
                      <div class="rank">#2</div>
                      <div class="fighter-name">Jugurtha</div>
                      <div class="fighter-record">0-0-0</div>
                    </div>

                </div>
              </div>

              <div class="category">
                <div class="category-title">Poids Lourds(+90kg)</div>
                <div class="champion">
                  <img class="champion-image" src="/Images/randomwhite.png" alt="Champion Boxe">
                  <div class="champion-name">#C NA</div>
                </div>
                <div class="fighters">
                 
                  <div class="fighter">
                    <div class="rank">#1</div>  
                    <div class="fighter-name">Kevin Bouleau</div>
                    <div class="fighter-record">0-0-0</div>
                  </div>
                  
                    <div class="fighter">
                      <div class="rank">#2</div>
                      <div class="fighter-name">Abdel</div>
                      <div class="fighter-record">0-0-0</div>
                    </div>
                    
                </div>
              </div>

          </div>
          <?php include'../../footer.php' ?>
    </body>
</html>