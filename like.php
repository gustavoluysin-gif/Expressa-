<?php
require 'db.php';
header('Content-Type: application/json');
$user = current_user();
if (!$user) { echo json_encode(['ok'=>false,'error'=>'login']); exit; }
$post_id = (int)($_POST['post_id'] ?? 0);
if (!$post_id) { echo json_encode(['ok'=>false]); exit; }
try {
    $stmt = $pdo->prepare('INSERT INTO curtida (idUser, idObra) VALUES (?, ?)');
    $stmt->execute([$user['idUser'], $post_id]);
    $postOwner = $pdo->prepare('SELECT idUser FROM obra WHERE idObra = ?');
    $postOwner->execute([$post_id]);
    $row = $postOwner->fetch();
    if ($row && $row['idUser'] != $user['idUser']) {
        $p = $pdo->prepare('INSERT INTO notificacao (user_id, type, from_user_id, post_id) VALUES (?, ?, ?, ?)');
        $p->execute([$row['idUser'], 'like', $user['idUser'], $post_id]);
    }
    echo json_encode(['ok'=>true, 'action'=>'liked']);
} catch (PDOException $e) {
    $del = $pdo->prepare('DELETE FROM curtida WHERE idUser = ? AND idObra = ?');
    $del->execute([$user['idUser'], $post_id]);
    echo json_encode(['ok'=>true, 'action'=>'unliked']);
}
