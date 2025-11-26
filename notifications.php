<?php
require 'db.php';
$user = current_user();
if (!$user) { header('Location: login.php'); exit; }
$stmt = $pdo->prepare('SELECT n.*, u.nomeUser AS from_username FROM notificacao n LEFT JOIN usuario u ON u.idUser = n.from_user_id WHERE n.user_id = ? ORDER BY n.created_at DESC');
$stmt->execute([$user['idUser']]);
$notes = $stmt->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Notificações - Expressa+</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<header class="container"><div class="logo"><img src="assets/images/logo.png" class="logo-img"></div><nav>Olá, <?php echo htmlspecialchars($user['nomeUser']); ?> | <a href="index.php">Explorar</a> | <a href="profile.php?id=<?php echo $user['idUser']; ?>">Meu Perfil</a> | <a href="logout.php">Sair</a></nav></header>
<div class="container"><h1 class="titulo-pagina">Notificações</h1><div class="notif-box"><?php foreach($notes as $n): ?><div class="notif-item"><?php if ($n['type']=='like'): ?><strong><?php echo htmlspecialchars($n['from_username']); ?></strong> curtiu sua foto.<?php else: ?><strong><?php echo htmlspecialchars($n['from_username']); ?></strong> comentou sua foto.<?php endif; ?><div style="font-size:0.85rem;color:#dcd0ff"><?php echo $n['created_at']; ?></div></div><?php endforeach; ?></div></div></body></html>
