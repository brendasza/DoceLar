<?php
require_once '../CORE/conexao.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$conn = Conexao::getConexao();
$id_usuario = $_SESSION['id_usuario'];

$query = $conn->prepare("
    SELECT nome, email, telefone, cidade, estado 
    FROM USUARIO 
    WHERE id_usuario = ?
");
$query->bind_param("i", $id_usuario);
$query->execute();
$query->bind_result($nome, $email, $telefone, $cidade, $estado);
$query->fetch();
$query->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Formulário de Adoção</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f6f6f7;
    padding: 20px;
    display: flex;
    justify-content: center;
}

form {
    width: 480px;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.08);
}

h2 {
    text-align: center;
    color: #5b2e91;
}

label {
    margin-top: 12px;
    display: block;
    font-weight: bold;
    font-size: 14px;
}

input, select, textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 8px;
    border: 1px solid #aaa;
}

textarea {
    height: 90px;
    resize: none;
}

button {
    margin-top: 15px;
    padding: 12px;
    width: 100%;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
}

.btn-enviar {
    background: #5b2e91;
    color: #fff;
}

.btn-voltar {
    background: #777;
    color: #fff;
    margin-top: 8px;
}

/* Modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    display: none;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    text-align: center;
    width: 350px;
}

.modal-content button {
    width: 100%;
    margin-top: 10px;
    background: #5b2e91;
    color: #fff;
}
</style>
</head>

<body>

<form id="formAdocao">
    <h2>Solicitação de Adoção</h2>

    <label>Nome Completo</label>
    <input type="text" value="<?= $nome ?>" readonly>

    <label>Email</label>
    <input type="email" value="<?= $email ?>" readonly>

    <label>Telefone</label>
    <input type="text" value="<?= $telefone ?>" readonly>

    <label>Cidade</label>
    <input type="text" value="<?= $cidade ?>" readonly>

    <label>Estado</label>
    <input type="text" value="<?= $estado ?>" readonly>

    <label>Rua</label>
    <input type="text" name="rua" required placeholder="Digite sua rua">

    <label>Bairro</label>
    <input type="text" name="bairro" required placeholder="Digite seu bairro">

    <label>Por que deseja adotar este animal?</label>
    <textarea name="motivo" required></textarea>

    <label>Você já teve animais antes?</label>
    <select name="pets" required>
        <option value="">Selecione</option>
        <option>Sim</option>
        <option>Não</option>
    </select>

    <label>Qual o tipo de moradia?</label>
    <select name="moradia" required>
        <option value="">Selecione</option>
        <option>Casa com quintal</option>
        <option>Apartamento</option>
        <option>Sítio/Chácara</option>
    </select>

    <button type="submit" class="btn-enviar">Enviar Solicitação</button>
    <button type="button" class="btn-voltar" onclick="history.back()">Voltar</button>
</form>


<!-- Modal -->
<div class="modal" id="modalEmailEnviado">
    <div class="modal-content">
        <p>Email enviado para a ONG responsável pelo animal!</p>
        <button class="btn-voltar" onclick="history.back()">Voltar</button>
    </div>
</div>


<script>
document.getElementById("formAdocao").addEventListener("submit", function(e) {
    e.preventDefault();

    // Exibe o modal
    document.getElementById("modalEmailEnviado").style.display = "flex";

    // Após 2 segundos, vai para termos.php
    setTimeout(() => {
        window.location.href = "termos.php";
    }, 2000);
});
</script>

</body>
</html>
