<?php
require_once '../CORE/conexao.php';
$conn = Conexao::getConexao();
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];
    $telefone = $_POST['telefone'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmarSenha'];

    if ($senha !== $confirmar) {
        $_SESSION['mensagem'] = "As senhas não coincidem!";
        $_SESSION['tipo_mensagem'] = "erro";
        header("Location: ../VIEW/cadastro.php");
        exit;
    }

    $senhaCript = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO USUARIO (nome, email, senha_hash, telefone, cidade, estado, tipo_usuario) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nome, $email, $senhaCript, $telefone, $cidade, $estado, $tipo_usuario);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Cadastro realizado com sucesso! Faça login para continuar.";
        $_SESSION['tipo_mensagem'] = "sucesso";
        header("Location: ../VIEW/login.php");
    } else {
        $_SESSION['mensagem'] = "Erro ao cadastrar: " . $stmt->error;
        $_SESSION['tipo_mensagem'] = "erro";
        header("Location: ../VIEW/cadastro.php");
    }

    $stmt->close();
}

$conn->close();
?>
