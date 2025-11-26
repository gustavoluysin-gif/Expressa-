<?php
require 'db.php';
$user = current_user();
$q = $_GET['q'] ?? '';
$tag = $_GET['tag'] ?? '';
$filter = $_GET['filter'] ?? '';

$sql = 'SELECT p.*, u.nomeUser, u.nome FROM obra p JOIN usuario u ON u.idUser = p.idUser WHERE 1=1';
$params = [];
if ($q) { $sql .= ' AND (p.descricao LIKE ? OR u.nomeUser LIKE ?)'; $params[] = "%$q%"; $params[] = "%$q%"; }
if ($tag) { $sql .= ' AND p.tags LIKE ?'; $params[] = "%$tag%"; }
if ($filter === 'recent') { $sql .= ' ORDER BY p.dataCriacao DESC'; } else { $sql .= ' ORDER BY p.dataCriacao DESC'; }
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Expressa+ - Explorar</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="container">
  <div class="logo"><img src="assets/images/logo.png" alt="Logo" class="logo-img"></div>
  <nav>
    <?php if ($user): ?>
      Olá, <?php echo htmlspecialchars($user['nomeUser']); ?> | <a href="post.php">Publicar</a> | <a href="notifications.php">Notificações</a> | <a href="profile.php?id=<?php echo $user['idUser']; ?>">Meu Perfil</a> | <a href="logout.php">Sair</a>
    <?php else: ?>
      <a href="login.php">Entrar</a> | <a href="register.php">Cadastrar</a>
    <?php endif; ?>
  </nav>
</header>

<div class="container">
  <h1 class="titulo-pagina">Explorar</h1>

  <div class="barra-filtros">
    <form method="get" class="filters-form" style="display:flex;gap:8px;width:100%">
      <input name="q" class="filtro-input" placeholder="Pesquisar descrição ou usuário" value="<?php echo htmlspecialchars($q); ?>">
      <input name="tag" class="filtro-input" placeholder="Tag" value="<?php echo htmlspecialchars($tag); ?>">
      <select name="filter" class="filtro-input" style="max-width:170px">
        <option value="">Ordenar</option>
        <option value="recent" <?php if($filter=='recent') echo 'selected'; ?>>Mais recentes</option>
      </select>
      <button class="btn-filtrar">Buscar</button>
    </form>
  </div>

  <div class="grid">
    <?php foreach($posts as $p):
      $blur = ($p['classificacao18'] && (!$user || $user['idade'] < 18));
    ?>
    <div class="card">
      <div class="image-wrap <?php echo $blur ? 'censurada' : ''; ?>" data-post="<?php echo $p['idObra']; ?>">
        <img src="<?php echo htmlspecialchars($p['imagem']); ?>" alt="">
        <?php if($blur): ?>
          <div class="blur-overlay">Conteúdo adulto - restrito</div>
        <?php endif; ?>
      </div>
      <div class="meta">
        <strong><?php echo htmlspecialchars($p['nomeUser']); ?></strong>
        <p><?php echo nl2br(htmlspecialchars($p['descricao'])); ?></p>
        <p class="tags"><?php echo htmlspecialchars($p['tags']); ?></p>
        <div class="acoes">
          <button class="btn btn-curtir" data-id="<?php echo $p['idObra']; ?>">Curtir</button>
          <input class="comment-input" placeholder="Comentar..." data-id="<?php echo $p['idObra']; ?>">
          <button class="btn btn-comentar" data-id="<?php echo $p['idObra']; ?>">Enviar</button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<script src="assets/js/app.js"></script>
</body>
</html>
