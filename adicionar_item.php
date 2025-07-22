<?php
require_once "includes/proteger.php";
require_once "includes/conexao.php";
require_once "includes/cabecalho.php";

// Buscar categorias
$sql = "SELECT * FROM categorias ORDER BY nome";
$categorias = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
    <h2>Adicionar Item</h2>
    <form action="actions/adicionar_item.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Autor</label>
            <input type="text" name="autor" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select" required>
                <option value="">Selecione</option>
                <option value="livro">Livro</option>
                <option value="revista">Revista</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Categoria</label>
            <select name="categoria_id" class="form-select" required>
                <option value="">Selecione</option>
                <?php while ($cat = $categorias->fetch_assoc()) { ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option> <!-- Exibe as categoriasdisponíveis -->
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Capa (opcional)</label>
            <input type="file" name="capa" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        
    </form>
</div>
</body>

<?php require_once "includes/rodape.php"; ?>
