<?php
session_start();
require_once '../CORE/conexao.php';
$conn = Conexao::getConexao();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Buscar dados do usuário
$sql = "SELECT nome, email, cidade, estado, tipo_usuario FROM USUARIO WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

$tipo = $usuario['tipo_usuario']; // ONG ou Adotante
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Perfil</title>
<link rel="stylesheet" href="css/perfil.css">
</head>
<body>

<div class="container">

    <!-- CARD DE INFORMAÇÕES DO USUÁRIO -->
    <div class="card">
        <h2>Meu Perfil</h2>
        <p><strong>Nome:</strong> <?= $usuario['nome'] ?></p>
        <p><strong>Email:</strong> <?= $usuario['email'] ?></p>
        <p><strong>Cidade:</strong> <?= $usuario['cidade'] ?> / <?= $usuario['estado'] ?></p>
        <p><strong>Tipo de Usuário:</strong> <?= $usuario['tipo_usuario'] ?></p>
    </div>

    <!-- SE FOR ONG -->
    <?php if ($tipo === "ONG"): ?>

        <h2 class="mt-4">Meus Animais Cadastrados</h2>

        <?php
        $sqlAnimais = "SELECT * FROM ANIMAL WHERE id_ong_abrigo = ? ORDER BY data_cadastro DESC";
        $stmt2 = $conn->prepare($sqlAnimais);
        $stmt2->bind_param("i", $id_usuario);
        $stmt2->execute();
        $animais = $stmt2->get_result();
        ?>

        <div class="animais-container">
            <?php while ($a = $animais->fetch_assoc()): ?>
                <div class="animal-card">
                    <img src="../<?= $a['foto'] ?>" alt="<?= $a['nome'] ?>">
                    <h3><?= $a['nome'] ?></h3>
                    <p><?= $a['descricao'] ?></p>

                    <a href="editar_animal.php?id=<?= $a['id_animal'] ?>" class="btn edit">Editar</a>
                    <a href="excluir_animal.php?id=<?= $a['id_animal'] ?>" class="btn delete"
                        onclick="return confirm('Tem certeza que deseja excluir este animal?')">
                        Excluir
                    </a>
                </div>
            <?php endwhile; ?>
        </div>

    <!-- SE FOR ADOTANTE -->
    <?php else: ?>

        <h2 class="mt-4">Animais Que Eu Reservei</h2>

        <?php
        $sqlReservas = "
            SELECT a.id_animal, a.nome, a.foto, a.descricao, u.nome AS ong_nome, u.email AS ong_email, u.telefone AS ong_telefone
            FROM RESERVA r
            JOIN ANIMAL a ON r.id_animal = a.id_animal
            JOIN USUARIO u ON a.id_ong_abrigo = u.id_usuario
            WHERE r.id_usuario = ?
            ORDER BY r.data_reserva DESC
        ";
        $stmt3 = $conn->prepare($sqlReservas);
        $stmt3->bind_param("i", $id_usuario);
        $stmt3->execute();
        $reservas = $stmt3->get_result();
        ?>

        <div class="animais-container">
            <?php if ($reservas->num_rows === 0): ?>
                <p>Você ainda não reservou nenhum animal.</p>
            <?php else: ?>
                <?php while ($r = $reservas->fetch_assoc()): ?>
                    <div class="animal-card">
                        <img src="../<?= $r['foto'] ?>" alt="<?= $r['nome'] ?>">
                        <h3><?= $r['nome'] ?></h3>
                        <p><?= $r['descricao'] ?></p>
                        <p><strong>ONG:</strong> <?= $r['ong_nome'] ?></p>
                        <p><strong>Email ONG:</strong> <?= $r['ong_email'] ?></p>
                        <p><strong>Telefone:</strong> <?= $r['ong_telefone'] ?></p>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

    <?php endif; ?>
</div>

<div class="btn-container">
    <a href="home.php" class="btn-voltar">← Voltar</a>
</div>

</body>
</html>
