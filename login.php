<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Doce Lar</title>
<link rel="stylesheet" href="css/login_cadastro.css">


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="auth-container">
<img src="../uploads/docelar.png" alt="DoceLar" class="logo">

<div class="form-card">
<h2>Entrar</h2>
<form method="post" action="../MODEL/processa_login.php">
<label for="loginUsuario">E-mail</label>
<input type="email" id="loginUsuario" name="loginUsuario" placeholder="Digite seu e-mail" required>

<label for="loginSenha">Senha</label>
<input type="password" id="loginSenha" name="loginSenha" placeholder="Digite sua senha" required>

<button type="submit" class="btn">Entrar</button>
</form>

<p class="link-text">Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
<div class="modal fade" id="modalMensagem" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white" style="background-color: #6f42c1;">
        <h5 class="modal-title">Atenção</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p id="textoMensagem"></p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn text-white" style="background-color: #6f42c1;" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php if(isset($_SESSION['mensagem'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
document.getElementById('textoMensagem').innerText = "<?= $_SESSION['mensagem']; ?>";
var modal = new bootstrap.Modal(document.getElementById('modalMensagem'));
modal.show();
});
</script>
<?php unset($_SESSION['mensagem']); endif; ?>
</body>
</html>
