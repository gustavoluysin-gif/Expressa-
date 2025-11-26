<?php
require 'db.php';
$user = current_user();
if (!$user) { header('Location: login.php'); exit; }
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Publicar - Expressa+</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<header class="container">
  <div class="logo"><img src="assets/images/logo.png" class="logo-img"></div>
  <nav>Olá, <?php echo htmlspecialchars($user['nomeUser']); ?> | <a href="index.php">Explorar</a> | <a href="profile.php?id=<?php echo $user['idUser']; ?>">Meu Perfil</a> | <a href="logout.php">Sair</a></nav>
</header>
<div class="container">
  <div class="form-box">
    <h2>Postar imagem</h2>
    <form action="upload_handler.php" method="post" enctype="multipart/form-data">
      <input type="file" name="image" accept="image/*" required>
      <input name="titulo" placeholder="Título" required>
      <textarea name="descricao" placeholder="Descrição"></textarea>
      <input name="tags" placeholder="tags separadas por vírgula (ex: natureza, praia)">
      <label style="display:block;margin-top:8px;"><input type="checkbox" name="is_adult" value="1"> Conteúdo adulto</label>
      <button type="submit">Publicar</button>
    </form>
  </div>
</div>
</body></html>
