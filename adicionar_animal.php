<?php
session_start();
require_once '../CORE/conexao.php';
$conn = Conexao::getConexao();

if ($_SESSION['tipo_usuario'] !== 'ONG') {
    header("Location: confirmar_usuario.php");
    exit;
}

// Variáveis para modal
$modalMessage = "";
$modalSuccess = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $especie = $_POST['especie'];
    $sexo = $_POST['sexo'];
    $data_nascimento = $_POST['data_nascimento'] ?: null;
    $porte = $_POST['porte'];
    $raca = $_POST['raca'];
    $descricao = $_POST['descricao'];
    $status_adocao = $_POST['status_adocao'];
    $cidade = $_POST['cidade'];
    $estado = strtoupper($_POST['estado']);
    $id_ong = $_SESSION['id_usuario'];

    $foto_caminho = null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
        $tipoArquivo = mime_content_type($_FILES['foto']['tmp_name']);

        if (!in_array($tipoArquivo, $permitidos)) {
            $modalMessage = 'Formato inválido! Envie JPG, PNG ou WEBP.';
        } elseif ($_FILES['foto']['size'] > 4 * 1024 * 1024) {
            $modalMessage = 'Imagem muito grande! Máximo 4MB.';
        } else {
            $pasta = "../uploads/";
            if (!is_dir($pasta)) mkdir($pasta, 0777, true);

            $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $nome_arquivo = uniqid("img_", true) . "." . strtolower($extensao);
            $caminho_arquivo = $pasta . $nome_arquivo;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_arquivo)) {
                $foto_caminho = "uploads/" . $nome_arquivo;
            }
        }
    }

    if ($modalMessage === "") {
        $sql = "INSERT INTO ANIMAL
            (nome, especie, sexo, data_nascimento, porte, raca, descricao, status_adocao, cidade, estado, id_ong_abrigo, foto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssss",
            $nome, $especie, $sexo, $data_nascimento, $porte, $raca,
            $descricao, $status_adocao, $cidade, $estado, $id_ong, $foto_caminho
        );

        if ($stmt->execute()) {
            $modalMessage = 'Animal cadastrado com sucesso!';
            $modalSuccess = true;
        } else {
            $modalMessage = 'Erro ao cadastrar o animal.';
        }
    }
}
?>

<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Cadastrar Animal — Adote um Amigo</title>
<link rel="stylesheet" href="css/adicionar_aimal.css">
<style>
/* Modal */
.modal-overlay {
    display: none;
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}
.modal {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    text-align: center;
    width: 400px;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
}
.modal button {
    margin-top: 15px;
    padding: 10px 20px;
    border: none;
    background: #5b2e91;
    color: #fff;
    font-weight: bold;
    border-radius: 8px;
    cursor: pointer;
}
.modal.success { border-top: 5px solid #28a745; }
.modal.error { border-top: 5px solid #dc3545; }
</style>
</head>
<body>
<h1>Cadastro de Animal</h1>

<form id="form-animal" enctype="multipart/form-data" method="post">
    <label for="nome">Nome do animal *</label>
    <input type="text" id="nome" name="nome" maxlength="50" required>

    <label for="especie">Espécie *</label>
    <select id="especie" name="especie" required>
      <option value="Cachorro">Cachorro</option>
    </select>

    <label for="sexo">Sexo *</label>
    <select id="sexo" name="sexo" required>
      <option value="">Selecione...</option>
      <option value="Macho">Macho</option>
      <option value="Fêmea">Fêmea</option>
    </select>

    <label for="data_nascimento">Data de nascimento</label>
    <input type="date" id="data_nascimento" name="data_nascimento">

    <label for="porte">Porte *</label>
    <select id="porte" name="porte" required>
      <option value="">Selecione...</option>
      <option value="Pequeno">Pequeno</option>
      <option value="Médio">Médio</option>
      <option value="Grande">Grande</option>
    </select>

    <label for="raca">Raça</label>
    <input type="text" id="raca" name="raca" maxlength="50">

    <label for="descricao">Descrição</label>
    <textarea id="descricao" name="descricao" placeholder="Fale um pouco sobre o animal..."></textarea>

    <label for="status_adocao">Status de adoção *</label>
    <select id="status_adocao" name="status_adocao" required>
      <option value="Disponível" selected>Disponível</option>
    </select>

    <label for="cidade">Cidade *</label>
    <input type="text" id="cidade" name="cidade" maxlength="50" required>

    <label for="estado">Estado (UF) *</label>
    <input type="text" id="estado" name="estado" maxlength="2" required style="text-transform:uppercase">

    <label for="foto">Foto do animal</label>
    <input type="file" id="foto" name="foto" accept="image/*">

    <button type="submit" class="btn">Cadastrar</button>
</form>

<!-- MODAL -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal <?= $modalSuccess ? 'success' : 'error' ?>" id="modalContent">
        <p><?= htmlspecialchars($modalMessage) ?></p>
        <button onclick="closeModal()">Fechar</button>
    </div>
</div>

<script>
function closeModal() {
    document.getElementById('modalOverlay').style.display = 'none';
    <?php if($modalSuccess): ?>
    window.location.href = 'pets.php';
    <?php endif; ?>
}

// Mostrar modal se houver mensagem
<?php if($modalMessage): ?>
document.getElementById('modalOverlay').style.display = 'flex';
<?php endif; ?>
</script>

</body>
</html>
