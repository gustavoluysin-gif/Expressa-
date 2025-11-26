<?php
// db.php - conecta com MySQL e fornece current_user()
// Ajuste $user / $pass se necessário (XAMPP padrão: root / '')
session_start();

$host = '127.0.0.1';
$db   = 'expressa';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Erro de conexão — mostra mensagem simples
    echo 'DB Connection failed: ' . $e->getMessage();
    exit;
}

/**
 * Retorna dados do usuário logado (ou null)
 * - usa $_SESSION['user_id'] definido no login
 */
function current_user() {
    global $pdo;
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare('SELECT u.idUser, u.nome, u.nomeUser, u.email, u.idade, p.imagemPerfil FROM usuario u LEFT JOIN perfil p ON p.idUser = u.idUser WHERE u.idUser = ?');
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
    return null;
}
?>
