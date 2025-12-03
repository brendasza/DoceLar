<?php 
session_start(); 
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Adote um Amigo — Home</title>
    <link rel="stylesheet" href="css/styles.css" />
</head>

<body>

    <header class="navbar">

        <div class="nav-left">
            <img src="../uploads/docelar.png" alt="DoceLar" class="logo">
        </div>

        <nav class="nav-links">
            <a href="pets.php" class="nav-item">Encontrar pets</a>
            <a href="sobre.php" class="nav-item">Sobre</a>
            <a href="home.php" class="nav-item">home</a>
        </nav>

        <a href="confirmar_usuario.php" class="botao-adicionar">Adicionar Animal</a>

        <div class="nav-right" id="nav-right">

            <?php if (isset($_SESSION['id_usuario'])): ?>

                <div class="user-area" style="display:flex; align-items:center; gap:10px;">
                    <?php
                    $nome = $_SESSION['nome'] ?? '';
                    $parts = explode(' ', trim($nome));
                    $initials = '';

                    foreach ($parts as $p) {
                        if ($p !== '') $initials .= mb_substr($p, 0, 1);
                    }

                    $initials = mb_strtoupper(mb_substr($initials, 0, 2));
                    ?>

                    <button id="avatar-btn" class="avatar-sm" aria-expanded="false">
                        <?php echo htmlspecialchars($initials); ?>
                    </button>

                    <span class="user-name" id="user-name">
                        <?php echo htmlspecialchars($_SESSION['nome']); ?>
                    </span>
                </div>

            <?php else: ?>

                <a href="login.php" class="btn btn-ghost" id="btn-login">Entrar</a>
                <a href="cadastro.php" class="btn btn-primary" id="btn-signup">Cadastrar</a>

            <?php endif; ?>

        </div>
    </header>

    <div id="profile-float" class="profile-float hidden" aria-hidden="true">
        <div class="profile-card">

            <div class="profile-header">

                <div class="avatar-large" id="pf-avatar">
                    <?php echo isset($_SESSION['nome']) ? htmlspecialchars($initials) : 'JP'; ?>
                </div>

                <div>
                    <strong id="pf-name">
                        <?php echo isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : ''; ?>
                    </strong>

                    <div id="pf-email" class="muted">
                        <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>
                    </div>
                </div>

            </div>

            <div class="profile-body">
                <p>
                    <strong>Telefone</strong><br>
                    <small id="pf-telefone">
                        <?php echo isset($_SESSION['telefone']) ? htmlspecialchars($_SESSION['telefone']) : '—'; ?>
                    </small>
                </p>

                <div class="profile-actions">
                    <a href="perfil.php" class="btn btn-ghost small">Meu Perfil</a>

                    <form method="post" action="logout.php" style="display:inline;">
                        <button id="btn-logout" class="btn btn-danger small" type="submit">Sair</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
