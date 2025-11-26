<?php
require 'db.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $age = (int)$_POST['age'];

    if (!$username || !$email || !$password) $errors[] = 'Preencha todos os campos.';

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO usuario (nome, nomeUser, email, senha, idade) VALUES (?, ?, ?, ?, ?)');
        try {
            $stmt->execute([$username, $username, $email, $hash, $age]);
            $userId = $pdo->lastInsertId();
            $p = $pdo->prepare('INSERT INTO perfil (idUser) VALUES (?)');
            $p->execute([$userId]);
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Erro: ' . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Cadastro - Expressa+</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="form-box">
  <h2>Cadastro</h2>
  <?php if ($errors): ?>
    <div class="errors"><?php echo implode('<br>', $errors); ?></div>
  <?php endif; ?>
  <form method="post">
    <input name="username" placeholder="Nome completo" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Senha" required>
    <input name="age" type="number" min="1" max="120" placeholder="Idade" required>
    <button type="submit">Cadastrar</button>
  </form>
  <p>Já tem conta? <a href="login.php">Entrar</a></p>
</div>
</body>
</html>
