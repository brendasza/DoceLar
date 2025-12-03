<?php
session_start();
require_once '../CORE/conexao.php';
$conn = Conexao::getConexao();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "Animal não encontrado.";
    exit;
}

$id_animal = intval($_GET['id']);
$id_usuario = intval($_SESSION['id_usuario']);

// Verifica se o animal pertence à ONG (REPARA: passamos os 2 parâmetros)
$sqlCheck = "SELECT * FROM ANIMAL WHERE id_animal = ? AND id_ong_abrigo = ?";
$stmt = $conn->prepare($sqlCheck);
if (!$stmt) {
    die("Erro no prepare: " . $conn->error);
}
$stmt->bind_param("ii", $id_animal, $id_usuario);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "Esse animal não pertence à sua ONG ou não existe.";
    exit;
}

$animal = $res->fetch_assoc();

// Atualizar
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Campos que você quiser permitir editar
    $nome = trim($_POST['nome']);
    $especie = $_POST['especie'] ?? $animal['especie'];
    $sexo = $_POST['sexo'] ?? $animal['sexo'];
    $data_nascimento = !empty($_POST['data_nascimento']) ? $_POST['data_nascimento'] : null;
    $porte = $_POST['porte'] ?? $animal['porte'];
    $raca = $_POST['raca'] ?? $animal['raca'];
    $descricao = $_POST['descricao'] ?? $animal['descricao'];
    $cidade = $_POST['cidade'] ?? $animal['cidade'];
    $estado = !empty($_POST['estado']) ? strtoupper($_POST['estado']) : $animal['estado'];
    $status_adocao = $_POST['status_adocao'] ?? $animal['status_adocao'];

    // Foto: manter a existente por padrão
    $foto_caminho = $animal['foto'];

    // Se enviaram nova foto, validar e mover
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0 && !empty($_FILES['foto']['tmp_name'])) {
        $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
        $tipoArquivo = mime_content_type($_FILES['foto']['tmp_name']);

        if (!in_array($tipoArquivo, $permitidos)) {
            echo "<script>alert('Formato inválido! Envie JPG, PNG ou WEBP.'); window.history.back();</script>";
            exit;
        }

        if ($_FILES['foto']['size'] > 4 * 1024 * 1024) {
            echo "<script>alert('Imagem muito grande! Máximo 4MB.'); window.history.back();</script>";
            exit;
        }

        $pasta = "../uploads/";
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);

        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nome_arquivo = uniqid("img_", true) . "." . strtolower($extensao);
        $caminho_arquivo = $pasta . $nome_arquivo;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_arquivo)) {
            // remover arquivo antigo (opcional)
            if (!empty($animal['foto']) && file_exists("../" . $animal['foto'])) {
                @unlink("../" . $animal['foto']);
            }
            // grava no formato usado pelo seu BD (sem ../)
            $foto_caminho = "uploads/" . $nome_arquivo;
        } else {
            echo "<script>alert('Falha ao enviar a imagem.'); window.history.back();</script>";
            exit;
        }
    }

    // UPDATE com todos os campos que permitir editar
    $sqlUpdate = "UPDATE ANIMAL SET 
        nome = ?, especie = ?, sexo = ?, data_nascimento = ?, porte = ?, raca = ?, descricao = ?, status_adocao = ?, cidade = ?, estado = ?, foto = ?
        WHERE id_animal = ? AND id_ong_abrigo = ?";
    $stmtU = $conn->prepare($sqlUpdate);
    if (!$stmtU) {
        die("Erro no prepare UPDATE: " . $conn->error);
    }
    $stmtU->bind_param(
        "sssssssssssii",
        $nome,
        $especie,
        $sexo,
        $data_nascimento,
        $porte,
        $raca,
        $descricao,
        $status_adocao,
        $cidade,
        $estado,
        $foto_caminho,
        $id_animal,
        $id_usuario
    );

    // Observação: mysqli bind_param não aceita null em alguns casos -- se $data_nascimento for null, passe "" ou trate antes.
    if ($stmtU->execute()) {
        header("Location: perfil.php");
        exit;
    } else {
        echo "Erro ao atualizar: " . $stmtU->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Editar Animal</title>
<link rel="stylesheet" href="css/perfil.css">
</head>
<body>

<div class="container">
    <h1>Editar Animal</h1>

    <form method="POST" enctype="multipart/form-data">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($animal['nome']) ?>" required><br><br>

        <label>Espécie:</label>
        <select name="especie">
            <option <?= $animal['especie'] === 'Cachorro' ? 'selected' : '' ?>>Cachorro</option>
            <option <?= $animal['especie'] === 'Gato' ? 'selected' : '' ?>>Gato</option>
            <option <?= $animal['especie'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
        </select><br><br>

        <label>Sexo:</label>
        <select name="sexo">
            <option <?= $animal['sexo'] === 'Macho' ? 'selected' : '' ?>>Macho</option>
            <option <?= $animal['sexo'] === 'Fêmea' ? 'selected' : '' ?>>Fêmea</option>
        </select><br><br>

        <label>Data de Nascimento:</label>
        <input type="date" name="data_nascimento" value="<?= htmlspecialchars($animal['data_nascimento']) ?>"><br><br>

        <label>Porte:</label>
        <select name="porte">
            <option <?= $animal['porte'] === 'Pequeno' ? 'selected' : '' ?>>Pequeno</option>
            <option <?= $animal['porte'] === 'Médio' ? 'selected' : '' ?>>Médio</option>
            <option <?= $animal['porte'] === 'Grande' ? 'selected' : '' ?>>Grande</option>
        </select><br><br>

        <label>Raça:</label>
        <input type="text" name="raca" value="<?= htmlspecialchars($animal['raca']) ?>"><br><br>

        <label>Descrição:</label><br>
        <textarea name="descricao" rows="5"><?= htmlspecialchars($animal['descricao']) ?></textarea><br><br>

        <label>Status:</label>
        <select name="status_adocao">
            <option <?= $animal['status_adocao'] === 'Disponível' ? 'selected' : '' ?>>Disponível</option>
            <option <?= $animal['status_adocao'] === 'reservado' ? 'selected' : '' ?>>reservado</option>
            <option <?= $animal['status_adocao'] === 'adotado' ? 'selected' : '' ?>>adotado</option>
        </select><br><br>

        <label>Cidade:</label>
        <input type="text" name="cidade" value="<?= htmlspecialchars($animal['cidade']) ?>"><br><br>

        <label>Estado (UF):</label>
        <input type="text" name="estado" maxlength="2" value="<?= htmlspecialchars($animal['estado']) ?>" style="text-transform:uppercase"><br><br>

        <label>Foto (envie somente se quiser trocar):</label>
        <input type="file" name="foto" accept="image/*"><br><br>

        <button type="submit" class="btn edit">Salvar Alterações</button>
    </form>
</div>

</body>
</html>
