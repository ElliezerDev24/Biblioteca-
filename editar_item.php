<?php
require_once "includes/proteger.php";
require_once "includes/conexao.php";
require_once "includes/cabecalho.php";

// Verifica se o ID do item foi passado
$id = $_GET['id'] ?? 0;
$id_usuario = $_SESSION['id_usuario'];


// Buscar o item específico
$sql = "SELECT * FROM itens WHERE id = ? AND id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $id_usuario);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

// Verifica se o item existe
$categorias = $conn->query("SELECT * FROM categorias ORDER BY nome");

if (!$item) {
    echo "<script>alert('Item não encontrado.'); location.href='home.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container mt-4">
    <h2>Editar Item</h2>
    <form action="actions/editar_item.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $item['id'] ?>">
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($item['titulo']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Autor</label>
            <input type="text" name="autor" class="form-control" value="<?= htmlspecialchars($item['autor']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control" required><?= htmlspecialchars($item['descricao']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select" required>
                <option value="livro" <?= $item['tipo'] == 'livro' ? 'selected' : '' ?>>Livro</option>
                <option value="revista" <?= $item['tipo'] == 'revista' ? 'selected' : '' ?>>Revista</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Categoria</label>
            <select name="categoria_id" class="form-select" required>
                <?php while ($cat = $categorias->fetch_assoc()) { ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $item['categoria_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nome']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Nova Capa (opcional)</label>
            <input type="file" name="capa" class="form-control">

            <div class="card-body">
                <a href="home.php" class="btn btn-danger btn-sm">Voltar</a>
                </div>

        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>

<?php require_once "includes/rodape.php"; ?>
