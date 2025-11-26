<?php
require 'db.php';
header('Content-Type: application/json');
$user = current_user();
if (!$user) { echo json_encode(['ok'=>false,'error'=>'login']); exit; }
$post_id = (int)($_POST['post_id'] ?? 0);
$comment = trim($_POST['comment'] ?? '');
if (!$post_id || !$comment) { echo json_encode(['ok'=>false]); exit; }
$stmt = $pdo->prepare('INSERT INTO comentario (idUser, idObra, comentario) VALUES (?, ?, ?)');
$stmt->execute([$user['idUser'], $post_id, $comment]);
$postOwner = $pdo->prepare('SELECT idUser FROM obra WHERE idObra = ?');
$postOwner->execute([$post_id]);
$row = $postOwner->fetch();
if ($row && $row['idUser'] != $user['idUser']) {
    $p = $pdo->prepare('INSERT INTO notificacao (user_id, type, from_user_id, post_id) VALUES (?, ?, ?, ?)');
    $p->execute([$row['idUser'], 'comment', $user['idUser'], $post_id]);
}
echo json_encode(['ok'=>true]);
