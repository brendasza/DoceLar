

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Orientações para Adoção</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .adocao-container {
      max-width: var(--max-width);
      margin: 30px auto;
      background: white;
      padding: 26px;
      border-radius: 14px;
      box-shadow: var(--shadow);
    }

    .adocao-container h1 {
      color: var(--purple);
      margin-bottom: 18px;
    }

    .steps {
      display: flex;
      flex-direction: column;
      gap: 16px;
      margin-bottom: 26px;
    }

    .step {
      background: var(--bg);
      padding: 18px;
      border-radius: 12px;
      border-left: 5px solid var(--purple);
    }

    .step span {
      font-weight: bold;
      color: var(--purple);
    }

    .btn-continuar {
      background: var(--purple);
      color: white;
      padding: 12px 22px;
      border-radius: 10px;
      text-decoration: none;
      font-size: 17px;
      display: inline-block;
      font-weight: 600;
      transition: 0.2s;
    }

    .btn-continuar:hover {
      transform: scale(1.05);
      background: #6e3aa4;
    }

    .voltar {
      display: inline-block;
      margin-top: 18px;
      color: var(--muted);
      text-decoration: none;
    }

    @media (max-width: 720px) {
      .adocao-container { padding: 18px; }
    }
  </style>
</head>
<body>

<?php include "barra.php"; ?>

<div class="adocao-container">
  <h1>Antes de Adotar 🐾</h1>
  <p>Siga os passos abaixo para realizar uma adoção com responsabilidade:</p>

  <div class="steps">
    <div class="step">
      <span>1️⃣ Preencha o formulário de adoção</span><br>
      Suas informações serão avaliadas pela ONG responsável.
    </div>

    <div class="step">
      <span>2️⃣ Concorde com os termos da adoção</span><br>
      Leia atentamente as responsabilidades do adotante.
    </div>

    <div class="step">
      <span>3️⃣ Contato com a ONG associada ao animal</span><br>
      A ONG fará uma entrevista e combinará o processo de entrega do pet.
    </div>
  </div>

  <!-- BOTÃO QUE LEVA AO FORMULÁRIO DE ADOÇÃO -->
  <a href="form_adocao.php" class="btn-continuar">Continuar ➜</a>

  <br>
  <a href="javascript:history.back()" class="voltar">← Voltar</a>
</div>

</body>
</html>
