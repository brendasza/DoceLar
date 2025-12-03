<?php
include 'barra.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Adote um Amigo</title>
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>

  
  <section class="carousel" aria-roledescription="carousel" id="main-carousel">
    <div class="slides">

      <div class="slide active" style="background-image: url('../uploads/dog1.jpeg');">
        <div class="slide-caption">
          <h2>Encontre seu novo amigo</h2>
          <p>Temos cães de todas as idades esperando por um lar.</p>
          <a href="pets.php" class="btn btn-light">Ver cães para adoção</a>
        </div>
      </div>

      <div class="slide" style="background-image: url('../uploads/dog2.jpeg');">
        <div class="slide-caption">
          <h2>Cuidado e carinho</h2>
          <p>Cada cão recebe atenção, vacinas e avaliação antes da adoção.</p>
          <a href="#about" class="btn btn-light">Saiba mais</a>
        </div>
      </div>

      <div class="slide" style="background-image: url('../uploads/dog3.jpeg');">
        <div class="slide-caption">
          <h2>Adotar transforma vidas</h2>
          <p>Adotar é amor que vira rotina e alegria em dobro.</p>
          <a href="pets.php" class="btn btn-light">Adote já</a>
        </div>
      </div>

    </div>

    <button class="carousel-btn prev" id="carousel-prev" aria-label="Anterior">&#10094;</button>
    <button class="carousel-btn next" id="carousel-next" aria-label="Próximo">&#10095;</button>

    <div class="carousel-indicators" id="carousel-indicators"></div>
  </section>


  
  <section class="why-adopt" aria-labelledby="why-title">
    <h2 id="why-title">Por que adotar?</h2>
    <div class="cards">
      <article class="card">
        <div class="card-ill">
          <img src="../uploads/cachorro_rua1.png" alt="amor por pets">
        </div>
        <div class="card-content">
          <h3>Nesse exato momento,</h3>
          <p>existem milhares de doguinhos e gatinhos esperando um humano para chamar de seu.</p>
        </div>
      </article>

      <article class="card">
        <div class="card-ill">
          <img src="../uploads/cachorro_rua2.png" alt="recompensa">
        </div>
        <div class="card-content">
          <h3>E não há recompensa maior</h3>
          <p>do que vê-los se tornando aquela coisinha alegre e saudável depois de cuidado e carinho.</p>
        </div>
      </article>

      <article class="card">
        <div class="card-ill">
          <img src="../uploads/cachorro_rua3.png" alt="mudar destino">
        </div>
        <div class="card-content">
          <h3>Pensando bem, a pergunta é outra:</h3>
          <p>se você pode mudar o destino de um animal de rua, por que não faria isso?</p>
        </div>
      </article>
    </div>
<br><br>
    <div class="center">
      <a href="pets.php" class="btn btn-primary large">Encontrar meu novo amigo</a>
    </div>
  </section>



  <section id="about" class="about">
    <div class="container">
      <h2>Sobre o projeto</h2>
      <p class="lead">Somos uma plataforma que conecta abrigos e pessoas dispostas a adotar. Verificamos e cuidamos dos animais antes de colocá-los para adoção e oferecemos suporte no pós-adoção.</p>
      <div class="center">
        <a href="sobre.php" class="btn btn-ghost">Mais informações</a>
      </div>
    </div>
  </section>


  <footer class="site-footer">
    <div class="footer-bottom">
      <small>© <span id="year"></span> Doce Lar — Todos os direitos reservados.</small>
    </div>
  </footer>


    <script>
    
    (function(){
      const avatarBtn = document.getElementById('avatar-btn');
      const profileFloat = document.getElementById('profile-float');
      if (!avatarBtn || !profileFloat) return;

      avatarBtn.addEventListener('click', function(e){
        e.stopPropagation();
        profileFloat.classList.toggle('hidden');
        avatarBtn.setAttribute('aria-expanded', !profileFloat.classList.contains('hidden'));
      });

      document.addEventListener('click', function(e){
        if (!profileFloat.classList.contains('hidden')) {
          const inside = profileFloat.contains(e.target) || avatarBtn.contains(e.target);
          if (!inside) profileFloat.classList.add('hidden');
        }
      });
    })();
  </script>

  <script src="script.js"></script>
</body>
</html>
