<?php
session_start();
require_once '../CORE/conexao.php';
$conn = Conexao::getConexao();

if (!isset($_SESSION['id_usuario'])) {
    die("Usuário não logado.");
}

$idAdotante = $_SESSION['id_usuario'];
$idAnimal = $_SESSION['id_animal'] ?? null;

if (!$idAnimal) {
    die("Animal não informado.");
}

// Verifica se já existe uma reserva
$check = $conn->prepare("SELECT id_reserva FROM RESERVA WHERE id_usuario = ? AND id_animal = ?");
$check->bind_param("ii", $idAdotante, $idAnimal);
$check->execute();
$exists = $check->get_result();

if ($exists->num_rows === 0) {
    // Inserir reserva
    $insert = $conn->prepare("
        INSERT INTO RESERVA (id_usuario, id_animal, data_reserva)
        VALUES (?, ?, NOW())
    ");
    $insert->bind_param("ii", $idAdotante, $idAnimal);
    $insert->execute();
}

// Buscar dados para exibição
$query = $conn->prepare("
    SELECT a.nome AS animal_nome, u.nome AS ong_nome, u.email AS ong_email, u.telefone AS ong_telefone
    FROM ANIMAL a
    JOIN USUARIO u ON a.id_ong_abrigo = u.id_usuario
    WHERE a.id_animal = ?
");
$query->bind_param("i", $idAnimal);
$query->execute();
$dados = $query->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Adoção Pré-Confirmada</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f6f6f7;
    padding: 30px;
    text-align: center;
}
.container {
    background: #fff;
    padding: 25px;
    width: 500px;
    margin: auto;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
h2 {
    color: #5b2e91;
}
p {
    font-size: 16px;
    margin-top: 10px;
}
.btn-home {
    background: #5b2e91;
}
.btn-perfil {
    background: #777;
}
</style>
</head>
<body>
<div class="container">
    <h2>🎉 Adoção Pré-Confirmada!</h2>

    <p>O animal <strong><?= $dados['animal_nome'] ?></strong> foi <strong>reservado para você</strong>!</p>

    <p>Agora entre em contato com a ONG:</p>

    <p><strong>ONG:</strong> <?= $dados['ong_nome'] ?></p>
    <p><strong>Email:</strong> <?= $dados['ong_email'] ?></p>
    <p><strong>Telefone:</strong> <?= $dados['ong_telefone'] ?></p>
</div>
<div class="btn-area">
        <button class="btn btn-home" onclick="window.location.href='home.php'">Voltar para Home</button>
        <button class="btn btn-perfil" onclick="window.location.href='perfil.php'">Ir para Meu Perfil</button>
    </div>
</div>
</body>
</html>
