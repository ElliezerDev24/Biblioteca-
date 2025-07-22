<?php
if (!isset($_SESSION)) session_start();
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="home.php">Biblioteca</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">

                <li class="nav-item"><a class="nav-link" href="home.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="adicionar_item.php">Adicionar Item</a></li>
                <li class="nav-item"><a class="nav-link" href="categorias.php">Categorias</a></li>
                <li class="nav-item"><a class="nav-link" href="favoritos.php">Favoritos</a></li>
                <li class="nav-item"><a class="nav-link" href="carrinho.php">Carrinho</a></li>
                <li class="nav-item"><a class="nav-link" href="operacoes.php">Operações</a></li>

            </ul>
            <span class="navbar-text text-white me-3">
                Olá, <?= htmlspecialchars($_SESSION['nome_usuario'] ?? '') ?>
            </span>
            <a href="actions/logout.php" class="btn btn-outline-light">Sair</a>
        </div>
    </div>
</nav>
