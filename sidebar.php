<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
$perfil = $_SESSION['perfil'] ?? 'cliente';
?>
<div class="w-64 bg-amber-800 text-white min-h-screen flex flex-col justify-between shadow-xl border-r-4 border-amber-950">
    <div class="p-5">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-black text-yellow-400 tracking-widest uppercase">Quarto do Andy</h2>
            <span class="text-xs bg-yellow-500 text-amber-950 font-bold px-2 py-1 rounded-full uppercase mt-1 inline-block"><?= $_SESSION['usuario'] ?></span>
        </div>
        <nav class="space-y-2">
            <a href="brinquedos.php" class="block py-2.5 px-4 rounded-xl hover:bg-amber-700 font-bold transition duration-200 flex items-center gap-2">🧸 Brinquedos</a>
            <a href="categorias.php" class="block py-2.5 px-4 rounded-xl hover:bg-amber-700 font-bold transition duration-200 flex items-center gap-2">📦 Categorias</a>
            <a href="donos.php" class="block py-2.5 px-4 rounded-xl hover:bg-amber-700 font-bold transition duration-200 flex items-center gap-2">👦 Donos/Humanos</a>
        </nav>
    </div>
    <div class="p-5">
        <a href="logout.php" class="block text-center py-2 px-4 bg-red-600 hover:bg-red-700 rounded-xl font-bold transition duration-200">Sair do Jogo</a>
    </div>
</div>