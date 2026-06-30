<?php
include 'config.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Usuários padrão estáticos conforme solicitado
    if ($usuario === 'admin' && $senha === 'admin123') {
        $_SESSION['usuario'] = 'Andy (Admin)';
        $_SESSION['perfil'] = 'admin';
        header('Location: brinquedos.php');
        exit;
    } elseif ($usuario === 'cliente' && $senha === 'cliente123') {
        $_SESSION['usuario'] = 'Brinquedo (Cliente)';
        $_SESSION['perfil'] = 'cliente';
        header('Location: brinquedos.php');
        exit;
    } else {
        $erro = "Usuário ou senha incorretos! Os brinquedos ganharam vida?";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Toy Story Shop - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-blue-400 to-blue-200 min-h-screen flex items-center justify-center font-sans">
    <div class="bg-white p-8 rounded-3xl shadow-2xl w-96 border-4 border-yellow-400">
        <div class="text-center mb-6">
            <h1 class="text-4xl font-extrabold text-yellow-500 tracking-wider uppercase drop-shadow-md">Toy Story</h1>
            <p class="text-gray-500 font-bold">Ao Infinito e Além!</p>
        </div>
        
        <?php if($erro): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-sm text-center font-semibold border border-red-300"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Usuário</label>
                <input type="text" name="usuario" required class="w-full px-3 py-2 border-2 border-blue-300 rounded-xl focus:outline-none focus:border-yellow-400" placeholder="admin ou cliente">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Senha</label>
                <input type="password" name="senha" required class="w-full px-3 py-2 border-2 border-blue-300 rounded-xl focus:outline-none focus:border-yellow-400" placeholder="admin123 ou cliente123">
            </div>
            <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-blue-900 font-extrabold py-3 rounded-xl transition duration-300 shadow-md uppercase">Abrir Baú de Brinquedos</button>
        </form>
    </div>
</body>
</html>