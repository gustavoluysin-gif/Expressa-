<?php
require 'db.php';
$user = current_user();
if (!$user) { header('Location: login.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('DELETE FROM usuario WHERE idUser = ?');
    $stmt->execute([$user['idUser']]);
    session_destroy();
    header('Location: register.php');
    exit;
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Excluir Conta - Expressa+</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<header class="container"><div class="logo"><img src="assets/images/logo.png" class="logo-img"></div><nav>Olá, <?php echo htmlspecialchars($user['nomeUser']); ?> | <a href="index.php">Explorar</a> | <a href="profile.php?id=<?php echo $user['idUser']; ?>">Meu Perfil</a> | <a href="logout.php">Sair</a></nav></header>
<div class="container"><div class="form-box"><h2>Excluir Conta</h2><p>Ao confirmar, sua conta será excluída permanentemente.</p><form method="post"><button type="submit" style="background:#c0392b">Confirmar exclusão</button></form></div></div></body></html>
