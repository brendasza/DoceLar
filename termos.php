<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

// Salva o id do animal na sessão
if (isset($_GET['id'])) {
    $_SESSION['id_animal'] = $_GET['id'];
}

// Pega o id do animal da sessão
$id_animal = $_SESSION['id_animal'] ?? null;

if (!$id_animal) {
    die("Animal não informado.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Termos de Adoção - Doce Lar</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f6f6f7;
        padding: 20px;
        display: flex;
        justify-content: center;
    }

    .container {
        width: 750px;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.08);
    }

    h2 {
        text-align: center;
        color: #5b2e91;
        margin-bottom: 20px;
    }

    p {
        text-align: justify;
        line-height: 1.6;
        color: #333;
        margin-bottom: 15px;
    }

    .btn-area {
        display: flex;
        gap: 10px;
        margin-top: 25px;
        justify-content: center;
    }

    button {
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        font-size: 15px;
        color: #fff;
    }

    .btn-voltar {
        background: #777;
    }

    .btn-continuar {
        background: #5b2e91;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Termos e Responsabilidades para Adoção</h2>

    <p>A adoção de um animal é um ato de amor e responsabilidade. Ao prosseguir com a adoção, você se compromete a
    oferecer um lar seguro, acolhedor e com as condições necessárias para o bem-estar do animal. A ONG responsável
    permanecerá disponível caso você precise de orientações ou apoio durante a adaptação.</p>

    <p>Você se compromete a fornecer alimentação adequada, água fresca, vacinação atualizada, acompanhamento
    veterinário periódico e todo cuidado que o animal necessitar. Também é importante garantir atenção,
    carinho, atividades e socialização.</p>

    <p>O animal não deverá ser submetido a maus-tratos, abandono ou negligência. Caso não seja possível manter a adoção
    no futuro, você concorda em contatar a ONG para que juntos encontrem uma solução responsável.</p>

    <p>Ao aceitar estes termos, você declara compreender que o animal é um ser vivo que sente dor, medo e carinho, e que
    precisa de cuidados durante toda sua vida. A adoção é uma decisão séria e deve ser tomada com consciência e
    comprometimento.</p>

    <div class="btn-area">
        <button class="btn-voltar" onclick="history.back()">Voltar</button>
        <button class="btn-continuar" onclick="window.location.href='confirmacao.php?id=<?= $id_animal ?>'">
            Aceito os Termos
        </button>
    </div>
</div>

</body>
</html>
