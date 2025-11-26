<?php
require 'db.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT idUser, senha FROM usuario WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['senha'])) {
        $_SESSION['user_id'] = $user['idUser'];
        header('Location: index.php');
        exit;
    } else {
        $errors[] = 'Credenciais inválidas.';
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Login - Expressa+</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<div class="form-box">
  <h2>Login</h2>
  <?php if ($errors): ?>
    <div class="errors"><?php echo implode('<br>', $errors); ?></div>
  <?php endif; ?>
  <form method="post">
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Senha" required>
    <button type="submit">Entrar</button>
  </form>
  <p>Não tem conta? <a href="register.php">Cadastre-se</a></p>
</div>
</body></html>
