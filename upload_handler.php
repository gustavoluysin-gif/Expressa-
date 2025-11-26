s<?php
require 'db.php';
$user = current_user();
if (!$user) { header('Location: login.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['image'])) { die('Arquivo não enviado'); }
    $f = $_FILES['image'];
    if ($f['error'] !== UPLOAD_ERR_OK) { die('Erro no upload'); }
    $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
    $allowed = ['jpg','jpeg','png','gif','webp'];
    if (!in_array(strtolower($ext), $allowed)) die('Tipo de arquivo não permitido');
    $name = uniqid() . '.' . $ext;
    $dest = __DIR__ . '/uploads/' . $name;
    if (!move_uploaded_file($f['tmp_name'], $dest)) die('Falha ao salvar');
    $titulo = $_POST['titulo'] ?? 'Sem título';
    $descricao = $_POST['descricao'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $is_adult = isset($_POST['is_adult']) ? 1 : 0;
    $stmt = $pdo->prepare('INSERT INTO obra (idUser, titulo, descricao, imagem, classificacao18, tags) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([ $user['idUser'], $titulo, $descricao, 'uploads/'.$name, $is_adult, $tags ]);
    header('Location: index.php');
    exit;
}
