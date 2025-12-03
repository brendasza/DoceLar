<?php
session_start();
require_once '../CORE/conexao.php';
$conn = Conexao::getConexao();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$mostrarModal = false;
$mensagemErro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $resposta = $_POST['resposta'];
    $id_usuario = $_SESSION['id_usuario'];

    if ($resposta === "sim") {

        $sql = "SELECT tipo_usuario FROM USUARIO WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && $user['tipo_usuario'] === 'ONG') {
            header("Location: adicionar_animal.php");
            exit;
        } else {
            $mostrarModal = true;
            $mensagemErro = "Você não está cadastrado como ONG. Apenas ONGs podem adicionar animais.";
        }

    } else {
        $mostrarModal = true;
        $mensagemErro = "Apenas ONGs podem adicionar animais.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Confirmação de Usuário</title>
  <link rel="stylesheet" href="css/confirmar_usuario.css" />

  <!-- INCLUIR BOOTSTRAP -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

</head>
<body>

  <div class="container mt-5">
    <h2>Confirmação</h2>
    <p>Você está cadastrado como ONG?</p>
    
    <form method="POST">
      <label class="me-3">
        <input type="radio" name="resposta" value="sim" required> Sim
      </label>
      <label>
        <input type="radio" name="resposta" value="nao"> Não
      </label>
      <br><br>
      <button type="submit" class="btn btn-primary">Confirmar Resposta</button>
    </form>
  </div>


  <!-- MODAL DE ERRO -->
  <div class="modal fade" id="erroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Atenção</h5>
        </div>
        <div class="modal-body">
          <?php echo $mensagemErro; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" id="btnFecharModal">OK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- JS Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <?php if ($mostrarModal): ?>
  <script>
      var modal = new bootstrap.Modal(document.getElementById('erroModal'));
      modal.show();

      document.getElementById("btnFecharModal").addEventListener("click", function () {
          window.location.href = "home.php";
      });
  </script>
  <?php endif; ?>

</body>
</html>
