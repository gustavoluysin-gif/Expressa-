<?php
require 'db.php';
$viewUserId = (int)($_GET['id'] ?? 0);
if (!$viewUserId) { header('Location: index.php'); exit; }
$stmt = $pdo->prepare('SELECT u.idUser, u.nome, u.nomeUser, p.biografia, p.meioContato, p.imagemPerfil FROM usuario u LEFT JOIN perfil p ON p.idUser = u.idUser WHERE u.idUser = ?');
$stmt->execute([$viewUserId]);
$profile = $stmt->fetch();
if (!$profile) { echo 'Usuário não encontrado'; exit; }
$user = current_user();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Perfil - Expressa+</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<header class="container"><div class="logo"><img src="assets/images/logo.png" class="logo-img"></div><nav><?php if ($user): ?>Olá, <?php echo htmlspecialchars($user['nomeUser']); ?> | <a href="index.php">Explorar</a> | <a href="logout.php">Sair</a><?php else: ?><a href="login.php">Entrar</a><?php endif; ?></nav></header>
<div class="container">
  <div style="display:flex;gap:18px;align-items:center;margin:18px 0">
    <img src="<?php echo 'uploads/'.htmlspecialchars($profile['imagemPerfil']); ?>" alt="Avatar" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,0.08)">
    <div>
      <h2><?php echo htmlspecialchars($profile['nomeUser']); ?></h2>
      <div><?php echo nl2br(htmlspecialchars($profile['biografia'])); ?></div>
      <div style="margin-top:8px;color:#dcd0ff"><?php echo htmlspecialchars($profile['meioContato']); ?></div>
      <?php if ($user && $user['idUser']==$viewUserId): ?>
        <div style="margin-top:10px"><a href="edit_profile.php">Editar Perfil</a> | <a href="delete_account.php" onclick="return confirm('Deseja realmente excluir sua conta? Esta ação é irreversível.')">Excluir Conta</a></div>
      <?php endif; ?>
    </div>
  </div>

  <h3>Obras</h3>
  <div class="grid">
    <?php
      $s = $pdo->prepare('SELECT * FROM obra WHERE idUser = ? ORDER BY dataCriacao DESC');
      $s->execute([$viewUserId]);
      $arts = $s->fetchAll();
      foreach($arts as $a):
    ?>
    <div class="card">
      <div class="image-wrap"><img src="<?php echo htmlspecialchars($a['imagem']); ?>" alt=""></div>
      <div class="meta"><strong><?php echo htmlspecialchars($a['titulo']); ?></strong><p><?php echo nl2br(htmlspecialchars($a['descricao'])); ?></p></div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
</body></html>
