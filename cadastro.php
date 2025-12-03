<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - Doce Lar</title>
  <link rel="stylesheet" href="css/login_cadastro.css">
</head>
<body>
  <div class="auth-container">
<img src="../uploads/docelar.png" alt="DoceLar" class="logo">

<div class="form-card">
      <h2>Cadastro</h2>

      <form method="post" action="../MODEL/processa_cadastro.php">
        <label for="nome">Nome completo</label>
        <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required>

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>

        <label for="estado">Estado</label>
        <select name="estado" id="estado" required>
          <option value="">Selecione o Estado</option> 
          <option value="AC">Acre</option> 
          <option value="AL">Alagoas</option> 
          <option value="AM">Amazonas</option> 
          <option value="AP">Amapá</option> 
          <option value="BA">Bahia</option> 
          <option value="CE">Ceará</option> 
          <option value="DF">Distrito Federal</option> 
          <option value="ES">Espírito Santo</option> 
          <option value="GO">Goiás</option> 
          <option value="MA">Maranhão</option> 
          <option value="MT">Mato Grosso</option> 
          <option value="MS">Mato Grosso do Sul</option> 
          <option value="MG">Minas Gerais</option> 
          <option value="PA">Pará</option> 
          <option value="PB">Paraíba</option> 
          <option value="PR">Paraná</option> 
          <option value="PE">Pernambuco</option> 
          <option value="PI">Piauí</option> 
          <option value="RJ">Rio de Janeiro</option> 
          <option value="RN">Rio Grande do Norte</option> 
          <option value="RO">Rondônia</option> 
          <option value="RS">Rio Grande do Sul</option> 
          <option value="RR">Roraima</option> 
          <option value="SC">Santa Catarina</option> 
          <option value="SE">Sergipe</option> 
          <option value="SP">São Paulo</option> 
          <option value="TO">Tocantins</option> 
        </select>

        <label for="Cidade">Cidade</label>
        <input type="text" id="cidade" name="cidade" placeholder="Digite sua cidade" required>

        <label for="telefone">Telefone</label>
        <input type="tel" id="telefone" name="telefone" placeholder="(xx) xxxxx-xxxx" required>

        <label for="tipo_usuario">Tipo de Usuário</label>
        <select name="tipo_usuario" id="tipo_usuario" required>
          <option value="">Selecione</option>
          <option value="Adotante">Adotante</option>
          <option value="ONG">ONG</option>
        </select>

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" placeholder="Crie uma senha" required>

        <label for="confirmarSenha">Confirmar senha</label>
        <input type="password" id="confirmarSenha" name="confirmarSenha" placeholder="Repita a senha" required>

        <button type="submit" class="btn">Cadastrar</button>
      </form>

      <p class="link-text">Já tem conta? <a href="login.php">Entre aqui</a></p>
    </div>
  </div>
</body>
</html>