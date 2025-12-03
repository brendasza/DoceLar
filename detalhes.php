<?php
session_start();
require_once '../CORE/conexao.php';
$conn = Conexao::getConexao();

if (!isset($_GET['id'])) {
    echo "Animal não encontrado.";
    exit;
}

$id = intval($_GET['id']);
$_SESSION['id_animal'] = $id; 

$sql = "SELECT * FROM ANIMAL WHERE id_animal = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Animal não encontrado.";
    exit;
}

$animal = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($animal['nome']); ?> — Detalhes</title>
    <link rel="stylesheet" href="css/detalhes.css">
</head>
<body>

<?php
$foto = !empty($animal['foto']) ? "../" . htmlspecialchars($animal['foto']) : "sem_foto.png";
?>

<div class="container">

    <div class="img-area">
        <img src="<?php echo $foto; ?>" alt="<?php echo htmlspecialchars($animal['nome']); ?>">
    </div>

    <div class="details">
        <h1><?php echo htmlspecialchars($animal['nome']); ?></h1>

        <p class="info"><span>Espécie:</span> <?php echo htmlspecialchars($animal['especie']); ?></p>
        <p class="info"><span>Sexo:</span> <?php echo htmlspecialchars($animal['sexo']); ?></p>
        <p class="info"><span>Porte:</span> <?php echo htmlspecialchars($animal['porte']); ?></p>
        <p class="info"><span>Raça:</span> <?php echo htmlspecialchars($animal['raca']); ?></p>
        <p class="info"><span>Localização:</span> 
            <?php echo htmlspecialchars($animal['cidade']); ?> - 
            <?php echo htmlspecialchars($animal['estado']); ?>
        </p>
        <p class="info"><span>Status:</span> <?php echo htmlspecialchars($animal['status_adocao']); ?></p>
        <p class="info"><span>Descrição:</span> 
            <?php echo nl2br(htmlspecialchars($animal['descricao'])); ?>
        </p>

        <?php if (isset($_SESSION['id_usuario'])): ?>
            <a class="btn-adotar" href="adocao.php?id=<?php echo $animal['id_animal']; ?>">Quero Adotar</a>
        <?php else: ?>
            <button class="btn-adotar" id="btnAbrirModal">Quero Adotar</button>
            
        <?php endif; ?>
        <br>
        <a href="pets.php" class="btn-voltar">← Voltar</a>
    </div>
    
</div>

<div id="modal-login" class="modal-overlay">
    <div class="modal-box">
        <p>Você precisa estar logada para solicitar a adoção.</p>

        <div class="modal-btns">
            <a href="login.php" class="btn-login">Fazer Login</a>
            <button id="btnFecharModal" class="btn-fechar">Fechar</button>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modal-login');
    const abrir = document.getElementById('btnAbrirModal');
    const fechar = document.getElementById('btnFecharModal');

    if (abrir && modal) {
        abrir.addEventListener('click', () => modal.style.display = 'flex');
    }

    if (fechar && modal) {
        fechar.addEventListener('click', () => modal.style.display = 'none');
    }
</script>

</body>
</html>
