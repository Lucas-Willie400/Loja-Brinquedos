<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$db   = 'toy_story_shop';
$user = 'root'; // Altere se necessário
$pass = '';     // Altere se necessário

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao baú de brinquedos: " . $e->getMessage());
}

// Função para proteger páginas
function verificarLogin() {
    if (!isset($_SESSION['usuario'])) {
        header('Location: index.php');
        exit;
    }
}
?>