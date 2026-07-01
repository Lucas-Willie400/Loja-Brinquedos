<?php
include 'config.php';
verificarLogin();

$brinquedo_para_editar = null;

// Lógica de Alteração/Update (Apenas Admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update' && $_SESSION['perfil'] === 'admin') {
    $stmt = $pdo->prepare("UPDATE brinquedos SET nome = ?, categoria_id = ?, dono_id = ?, status = ? WHERE id = ?");
    $stmt->execute([$_POST['nome'], $_POST['categoria_id'], $_POST['dono_id'], $_POST['status'], $_POST['id']]);
    header('Location: brinquedos.php');
    exit;
}

// Lógica de Inserção (Apenas Admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create' && $_SESSION['perfil'] === 'admin') {
    $stmt = $pdo->prepare("INSERT INTO brinquedos (nome, categoria_id, dono_id, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['nome'], $_POST['categoria_id'], $_POST['dono_id'], $_POST['status']]);
    header('Location: brinquedos.php');
    exit;
}

// Lógica de Deleção (Apenas Admin)
if (isset($_GET['delete']) && $_SESSION['perfil'] === 'admin') {
    $stmt = $pdo->prepare("DELETE FROM brinquedos WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: brinquedos.php');
    exit;
}

// Capturar brinquedo para edição (Se clicado em editar)
if (isset($_GET['edit']) && $_SESSION['perfil'] === 'admin') {
    $stmt = $pdo->prepare("SELECT * FROM brinquedos WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $brinquedo_para_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Buscar dados relacionados para a listagem
$brinquedos = $pdo->query("SELECT b.*, c.nome as categoria, d.nome as dono FROM brinquedos b 
                           LEFT JOIN categorias c ON b.categoria_id = c.id 
                           LEFT JOIN donos d ON b.dono_id = d.id")->fetchAll(PDO::FETCH_ASSOC);

$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
$donos = $pdo->query("SELECT * FROM donos")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Brinquedos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-sky-100 font-sans flex">

    <?php include 'sidebar.php'; ?>

    <div class="flex-1 p-10">
        <h1 class="text-4xl font-black text-blue-900 mb-6 uppercase tracking-wide">📦 Estoque de Brinquedos</h1>

        <?php if ($_SESSION['perfil'] === 'admin'): ?>
            
            <div class="bg-white p-6 rounded-2xl shadow-md mb-8 border-t-4 <?= $brinquedo_para_editar ? 'border-blue-500' : 'border-yellow-400' ?>">
                <h2 class="text-xl font-bold mb-4 text-amber-800">
                    <?= $brinquedo_para_editar ? '✏️ Editar Detalhes do Brinquedo' : '➕ Adicionar Novo Brinquedo ao Baú' ?>
                </h2>
                
                <form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="hidden" name="action" value="<?= $brinquedo_para_editar ? 'update' : 'create' ?>">
                    <?php if ($brinquedo_para_editar): ?>
                        <input type="hidden" name="id" value="<?= $brinquedo_para_editar['id'] ?>">
                    <?php endif; ?>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Nome do Brinquedo</label>
                        <input type="text" name="nome" value="<?= $brinquedo_para_editar['nome'] ?? '' ?>" required class="w-full p-2 border rounded-xl">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Categoria</label>
                        <select name="categoria_id" class="w-full p-2 border rounded-xl">
                            <option value="">Escolha uma categoria</option>
                            <?php foreach($categorias as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= isset($brinquedo_para_editar) && $brinquedo_para_editar['categoria_id'] == $cat['id'] ? 'selected' : '' ?>><?= $cat['nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Dono</label>
                        <select name="dono_id" class="w-full p-2 border rounded-xl">
                            <option value="">Quem é o dono?</option>
                            <?php foreach($donos as $dono): ?>
                                <option value="<?= $dono['id'] ?>" <?= isset($brinquedo_para_editar) && $brinquedo_para_editar['dono_id'] == $dono['id'] ? 'selected' : '' ?>><?= $dono['nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Status</label>
                        <input type="text" name="status" value="<?= $brinquedo_para_editar['status'] ?? 'Ativo' ?>" class="w-full p-2 border rounded-xl">
                    </div>

                    <div class="md:col-span-4 flex gap-2">
                        <button type="submit" class="flex-1 <?= $brinquedo_para_editar ? 'bg-blue-500 hover:bg-blue-600 text-white' : 'bg-yellow-400 hover:bg-yellow-500 text-blue-900' ?> font-bold py-2 rounded-xl shadow transition">
                            <?= $brinquedo_para_editar ? 'Atualizar Brinquedo' : 'Adicionar Brinquedo' ?>
                        </button>
                        <?php if ($brinquedo_para_editar): ?>
                            <a href="brinquedos.php" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-xl flex items-center shadow">Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-900 text-white uppercase text-sm">
                        <th class="p-4">ID</th>
                        <th class="p-4">Nome</th>
                        <th class="p-4">Categoria</th>
                        <th class="p-4">Dono</th>
                        <th class="p-4">Status</th>
                        <?php if($_SESSION['perfil'] === 'admin'): ?><th class="p-4 text-center">Ações</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach($brinquedos as $b): ?>
                        <tr class="hover:bg-yellow-50 font-medium text-gray-700">
                            <td class="p-4">#<?= $b['id'] ?></td>
                            <td class="p-4 font-bold text-blue-800"><?= htmlspecialchars($b['nome']) ?></td>
                            <td class="p-4"><span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs"><?= htmlspecialchars($b['categoria'] ?? 'Nenhuma') ?></span></td>
                            <td class="p-4 text-amber-700"><?= htmlspecialchars($b['dono'] ?? 'Ninguém (Livre)') ?></td>
                            <td class="p-4">
                                <span class="text-xs font-bold px-2 py-1 rounded-md bg-gray-100"><?= htmlspecialchars($b['status']) ?></span>
                            </td>
                            <?php if($_SESSION['perfil'] === 'admin'): ?>
                                <td class="p-4 text-center space-x-2">
                                    <a href="brinquedos.php?edit=<?= $b['id'] ?>" class="text-blue-500 hover:text-blue-700 font-bold bg-blue-50 px-2 py-1 rounded-lg border border-blue-200">Editar</a>
                                    <a href="brinquedos.php?delete=<?= $b['id'] ?>" class="text-red-500 hover:text-red-700 font-bold bg-red-50 px-2 py-1 rounded-lg border border-red-200" onclick="return confirm('Deseja mesmo doar este brinquedo?')">Doar</a>
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