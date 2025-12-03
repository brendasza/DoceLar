<?php
session_start();
require_once '../CORE/conexao.php';
$conn = Conexao::getConexao();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    exit("Animal não encontrado.");
}

$id_animal = intval($_GET['id']);
$id_usuario = $_SESSION['id_usuario'];

// Verifica se pertence à ONG
$sqlCheck = "SELECT foto FROM ANIMAL WHERE id_animal = ? AND id_ong_abrigo = ?";
$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param("i", $id_animal);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    exit("Você não tem permissão para excluir este animal.");
}

$animal = $res->fetch_assoc();

// Exclui
$sqlDelete = "DELETE FROM ANIMAL WHERE id_animal = ?";
$stmtD = $conn->prepare($sqlDelete);
$stmtD->bind_param("i", $id_animal);
$stmtD->execute();

if (file_exists($animal['foto'])) {
    unlink($animal['foto']);
}

header("Location: perfil.php");
exit;
?>
