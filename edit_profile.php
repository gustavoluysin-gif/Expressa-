<?php
require 'db.php';
$user = current_user();
if (!$user) { header('Location: login.php'); exit; }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $biografia = $_POST['biografia'] ?? '';
    $meio = $_POST['meioContato'] ?? '';
    if (!empty($_FILES['avatar']['name'])) {
        $f = $_FILES['avatar'];
        if ($f['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg','jpeg','png','gif','webp'];
            if (in_array(strtolower($ext), $allowed)) {
                $name = 'avatar_'. $user['idUser'] . '_' . uniqid() . '.' . $ext;
                $dest = __DIR__ . '/uploads/' . $name;
                if (move_uploaded_file($f['tmp_name'], $dest)) {
                    $p = $pdo->prepare('UPDATE perfil SET imagemPerfil = ? WHERE idUser = ?');
                    $p->execute([$name, $user['idUser']]);
                } else { $errors[] = 'Falha ao salvar imagem.'; }
            } else { $errors[] = 'Tipo de arquivo não permitido.'; }
        }
    }
    if (!$errors) {
        $p = $pdo->prepare('UPDATE perfil SET biografia = ?, meioContato = ? WHERE idUser = ?');
        $p->execute([$biografia, $meio, $user['idUser']]);
        header('Location: profile.php?id='.$user['idUser']);
        exit;
    }
}
$stmt = $pdo->prepare('SELECT p.* FROM perfil p WHERE p.idUser = ?');
$stmt->execute([$user['idUser']]);
$profile = $stmt->fetch();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Editar Perfil - Expressa+</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<header class="container"><div class="logo"><img src="assets/images/logo.png" class="logo-img"></div><nav>Olá, <?php echo htmlspecialchars($user['nomeUser']); ?> | <a href="index.php">Explorar</a> | <a href="profile.php?id=<?php echo $user['idUser']; ?>">Meu Perfil</a> | <a href="logout.php">Sair</a></nav></header>
<div class="container"><div class="form-box">
  <h2>Editar Perfil</h2>
  <?php if ($errors): ?><div class="errors"><?php echo implode('<br>', $errors); ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <label>Avatar (imagem circular):</label>
    <input type="file" name="avatar" accept="image/*">
    <label>Biografia:</label>
    <textarea name="biografia"><?php echo htmlspecialchars($profile['biografia'] ?? ''); ?></textarea>
    <input name="meioContato" placeholder="@contato" value="<?php echo htmlspecialchars($profile['meioContato'] ?? ''); ?>">
    <button type="submit">Salvar</button>
  </form>
</div></div></body></html>
