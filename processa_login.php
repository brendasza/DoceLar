<?php
require_once '../CORE/conexao.php';
$conn = Conexao::getConexao();
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['loginUsuario'])) {
    $email = $_POST['loginUsuario'];
    $senha = $_POST['loginSenha'];

    $sql = "SELECT * FROM USUARIO WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();

        if (password_verify($senha, $row['senha_hash'])) {

            $_SESSION['id_usuario']   = $row['id_usuario'];
            $_SESSION['nome']         = $row['nome'];
            $_SESSION['email']        = $row['email'];
            $_SESSION['telefone']     = $row['telefone'];
            $_SESSION['tipo_usuario'] = $row['tipo_usuario'];

            header("Location: ../VIEW/home.php");
            exit;

        } else {
            $_SESSION['mensagem'] = "Senha incorreta!";
            $_SESSION['tipo_mensagem'] = "erro";
            header("Location: ../VIEW/login.php");
            exit;
        }

    } else {
        $_SESSION['mensagem'] = "Usuário não encontrado!";
        $_SESSION['tipo_mensagem'] = "erro";
        header("Location: ../VIEW/login.php");
        exit;
    }

    $stmt->close();
}

$conn->close();
