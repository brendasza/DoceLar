<?php 
require_once '../CORE/conexao.php'; 
include 'barra.php';
$conn = Conexao::getConexao(); 

$estados_res = $conn->query("SELECT DISTINCT estado FROM ANIMAL WHERE estado IS NOT NULL AND estado <> '' ORDER BY estado");
$cidades_res = $conn->query("SELECT DISTINCT cidade FROM ANIMAL WHERE cidade IS NOT NULL AND cidade <> '' ORDER BY cidade");

$f_estado = $_GET['estado'] ?? '';
$f_cidade = $_GET['cidade'] ?? '';
$f_idade = $_GET['idade'] ?? '';
$f_sexo = $_GET['sexo'] ?? '';
$f_porte = $_GET['porte'] ?? '';

$conditions = [];

if (!empty($f_estado) && $f_estado !== 'Qualquer') {
    $estado_e = $conn->real_escape_string($f_estado);
    $conditions[] = "estado = '$estado_e'";
}

if (!empty($f_cidade) && $f_cidade !== 'Qualquer') {
    $cidade_e = $conn->real_escape_string($f_cidade);
    $conditions[] = "cidade = '$cidade_e'";
}

if (!empty($f_sexo) && $f_sexo !== 'Qualquer') {
    $sexo_e = $conn->real_escape_string($f_sexo);
    $conditions[] = "sexo = '$sexo_e'";
}

if (!empty($f_porte) && $f_porte !== 'Qualquer') {
    $porte_e = $conn->real_escape_string($f_porte);
    $conditions[] = "porte = '$porte_e'";
}

if (!empty($f_idade) && $f_idade !== 'Qualquer') {
    if ($f_idade === 'Bebê') {
        $conditions[] = "data_nascimento >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
    } elseif ($f_idade === 'Jovem') {
        $conditions[] = "data_nascimento BETWEEN DATE_SUB(CURDATE(), INTERVAL 3 YEAR) AND DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
    } elseif ($f_idade === 'Adulto') {
        $conditions[] = "data_nascimento BETWEEN DATE_SUB(CURDATE(), INTERVAL 8 YEAR) AND DATE_SUB(CURDATE(), INTERVAL 4 YEAR)";
    } elseif ($f_idade === 'Idoso') {
        $conditions[] = "data_nascimento <= DATE_SUB(CURDATE(), INTERVAL 9 YEAR)";
    }
}

$sql = "SELECT id_animal, nome, especie, sexo, data_nascimento, porte, cidade, estado, foto FROM ANIMAL";

if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY data_cadastro DESC";

$result = $conn->query($sql);
?> 

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Animais Disponíveis</title>
    <link rel="stylesheet" href="css/pets.css">
</head>
<body>

<div class="page-wrap">

    <aside class="filtros">
        <h2>Painel de Pesquisa</h2>
        <form method="get" id="form-filtros">

            <label for="estado">Estado (UF)</label>
            <select id="estado" name="estado">
                <option>Qualquer</option>
                <?php while ($e = $estados_res->fetch_assoc()): ?>
                    <option <?php echo ($f_estado === $e['estado']) ? 'selected' : '';?>>
                        <?php echo htmlspecialchars($e['estado']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="cidade">Cidade</label>
            <select id="cidade" name="cidade">
                <option>Qualquer</option>
                <?php while ($c = $cidades_res->fetch_assoc()): ?>
                    <option <?php echo ($f_cidade === $c['cidade']) ? 'selected' : '';?>>
                        <?php echo htmlspecialchars($c['cidade']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="idade">Idade</label>
            <select id="idade" name="idade">
                <option>Qualquer</option>
                <option <?php if($f_idade==='Bebê') echo 'selected'; ?>>Bebê (0 - 1 anos) </option>
                <option <?php if($f_idade==='Jovem') echo 'selected'; ?>>Jovem (1 - 3 anos) </option>
                <option <?php if($f_idade==='Adulto') echo 'selected'; ?>>Adulto (4 - 8 anos)</option>
                <option <?php if($f_idade==='Idoso') echo 'selected'; ?>>Idoso (+9 anos)</option>
            </select>

            <label for="sexo">Sexo</label>
            <select id="sexo" name="sexo">
                <option>Qualquer</option>
                <option <?php if($f_sexo==='Macho') echo 'selected'; ?>>Macho</option>
                <option <?php if($f_sexo==='Fêmea') echo 'selected'; ?>>Fêmea</option>
            </select>

            <label for="porte">Porte</label>
            <select id="porte" name="porte">
                <option>Qualquer</option>
                <option <?php if($f_porte==='Pequeno') echo 'selected'; ?>>Pequeno</option>
                <option <?php if($f_porte==='Médio') echo 'selected'; ?>>Médio</option>
                <option <?php if($f_porte==='Grande') echo 'selected'; ?>>Grande</option>
            </select>

            <button type="submit" class="btn-filtrar">Filtrar</button>
            <a href="pets.php" class="btn-limpar">Limpar</a>

        </form>
    </aside>

    <main class="lista-animais">

        <h1>Animais Disponíveis para Adoção</h1>

        <?php if ($result && $result->num_rows === 0): ?>
            <p class="sem-resultados">Nenhum animal encontrado com esses filtros.</p>
        <?php endif; ?>

        <div class="cards-container">

            <?php while ($row = $result->fetch_assoc()): ?>

                <?php 
                $foto = (!empty($row['foto'])) ? "../" . htmlspecialchars($row['foto']) : "sem_foto.png";

                $idade_texto = 'Idade não informada';
                if (!empty($row['data_nascimento'])) {
                    $birth = new DateTime($row['data_nascimento']);
                    $today = new DateTime();
                    $age = $today->diff($birth)->y;
                    $idade_texto = $age . ' ano' . ($age !== 1 ? 's' : '');
                }
                ?>

                <article class="card">
                    <div class="card-media">
                        <img class="card-img" src="<?php echo $foto; ?>" alt="Foto de <?php echo htmlspecialchars($row['nome']); ?>">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($row['nome']); ?></h3>
                        <p class="card-info">
                            <?php echo htmlspecialchars($row['especie']); ?> — <?php echo htmlspecialchars($row['porte']); ?><br>
                            <?php echo $idade_texto; ?><br>
                            <?php echo htmlspecialchars($row['cidade']) . ' - ' . htmlspecialchars($row['estado']); ?>
                        </p>
                        <a href="detalhes.php?id=<?php echo $row['id_animal']; ?>" class="btn-adotar">Mais detalhes</a>
                    </div>
                </article>

            <?php endwhile; ?>

        </div>

    </main>

</div>

</body>
</html>
