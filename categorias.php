<?php
include 'config.php';
verificarLogin();

$categoria_para_editar = null;

// Lógica de Alteração/Update (Apenas Admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update' && $_SESSION['perfil'] === 'admin') {
    $stmt = $pdo->prepare("UPDATE categorias SET nome = ?, descricao = ? WHERE id = ?");
    $stmt->execute([$_POST['nome'], $_POST['descricao'], $_POST['id']]);
    header('Location: categorias.php');
    exit;
}

// Lógica de Inserção (Apenas Admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create' && $_SESSION['perfil'] === 'admin') {
    $stmt = $pdo->prepare("INSERT INTO categorias (nome, descricao) VALUES (?, ?)");
    $stmt->execute([$_POST['nome'], $_POST['descricao']]);
    header('Location: categorias.php');
    exit;
}

// Lógica de Deleção (Apenas Admin)
if (isset($_GET['delete']) && $_SESSION['perfil'] === 'admin') {
    $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: categorias.php');
    exit;
}

// Capturar categoria para edição
if (isset($_GET['edit']) && $_SESSION['perfil'] === 'admin') {
    $stmt = $pdo->prepare("SELECT * FROM categorias WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $categoria_para_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Buscar todas as categorias
$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Toy Story Shop - Gerenciar Categorias</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-sky-100 font-sans flex">

    <?php include 'sidebar.php'; ?>

    <div class="flex-1 p-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-4xl font-black text-blue-900 uppercase tracking-wide">📦 Categorias de Brinquedos</h1>
        </div>

        <?php if ($_SESSION['perfil'] === 'admin'): ?>
            <div class="bg-white p-6 rounded-2xl shadow-md mb-8 border-t-4 <?= $categoria_para_editar ? 'border-blue-500' : 'border-yellow-400' ?>">
                <h2 class="text-xl font-bold mb-4 text-amber-800">
                    <?= $categoria_para_editar ? '✏️ Editar Seção/Categoria' : '➕ Organizar o Baú (Nova Categoria)' ?>
                </h2>
                <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="hidden" name="action" value="<?= $categoria_para_editar ? 'update' : 'create' ?>">
                    <?php if ($categoria_para_editar): ?>
                        <input type="hidden" name="id" value="<?= $categoria_para_editar['id'] ?>">
                    <?php endif; ?>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nome da Categoria</label>
                        <input type="text" name="nome" value="<?= $categoria_para_editar['nome'] ?? '' ?>" required 
                               class="w-full p-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-yellow-400">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Descrição</label>
                        <input type="text" name="descricao" value="<?= $categoria_para_editar['descricao'] ?? '' ?>" required
                               class="w-full p-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-yellow-400">
                    </div>
                    
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 <?= $categoria_para_editar ? 'bg-blue-500 hover:bg-blue-600 text-white' : 'bg-yellow-400 hover:bg-yellow-500 text-blue-900' ?> font-extrabold py-2 px-4 rounded-xl shadow transition uppercase tracking-wider">
                            <?= $categoria_para_editar ? 'Atualizar' : 'Criar' ?>
                        </button>
                        <?php if ($categoria_para_editar): ?>
                            <a href="categorias.php" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-xl flex items-center shadow">Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-900 text-white uppercase text-sm tracking-wider">
                        <th class="p-4 w-20">ID</th>
                        <th class="p-4">Nome da Categoria</th>
                        <th class="p-4">Descrição</th>
                        <?php if($_SESSION['perfil'] === 'admin'): ?><th class="p-4 text-center w-60">Ações</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach($categorias as $cat): ?>
                        <tr class="hover:bg-yellow-50 font-medium text-gray-700">
                            <td class="p-4 text-gray-400">#<?= $cat['id'] ?></td>
                            <td class="p-4 font-bold text-blue-800">🏷️ <?= htmlspecialchars($cat['nome']) ?></td>
                            <td class="p-4 text-gray-600"><?= htmlspecialchars($cat['descricao']) ?></td>
                            <?php if($_SESSION['perfil'] === 'admin'): ?>
                                <td class="p-4 text-center space-x-2">
                                    <a href="categorias.php?edit=<?= $cat['id'] ?>" class="text-blue-500 hover:text-blue-700 font-bold bg-blue-50 px-2 py-1 rounded-lg border border-blue-200">Editar</a>
                                    <a href="categorias.php?delete=<?= $cat['id'] ?>" class="text-red-600 hover:text-red-800 font-bold bg-red-50 px-2 py-1 rounded-lg border border-red-200" onclick="return confirm('Remover esta categoria?')">Remover</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>