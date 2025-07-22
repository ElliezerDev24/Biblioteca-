<?php
include 'includes/conexao.php';
include 'includes/cabecalho.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php?page=login");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Adicionar categoria
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nova_categoria'])) {
    $nome = trim($_POST['nome']);

    if (!empty($nome)) {
        $stmt = $conn->prepare("INSERT INTO categorias (nome, id_usuario) VALUES (?, ?)");
        $stmt->bind_param("si", $nome, $id_usuario);
        $stmt->execute();
        echo "<script>alert('Categoria adicionada com sucesso.'); window.location.href='index.php?page=categorias';</script>";
        exit();
    } else {
        echo "<div class='container mt-3 alert alert-danger'>Preencha o nome da categoria.</div>";
    }
}

// Editar categoria
if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $stmt = $conn->prepare("SELECT nome FROM categorias WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $id_editar, $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $categoria_editar = $resultado->fetch_assoc();
    } else {
        echo "<div class='container mt-3 alert alert-warning'>Categoria não encontrada.</div>";
    }
}

// Atualizar categoria
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar_categoria'])) {
    $id_categoria = $_POST['id_categoria'];
    $novo_nome = trim($_POST['nome']);

    if (!empty($novo_nome)) {
        $stmt = $conn->prepare("UPDATE categorias SET nome = ? WHERE id = ? AND id_usuario = ?");
        $stmt->bind_param("sii", $novo_nome, $id_categoria, $id_usuario);
        $stmt->execute();
        echo "<script>alert('Categoria atualizada com sucesso.'); window.location.href='index.php?page=categorias';</script>";
        exit();
    } else {
        echo "<div class='container mt-3 alert alert-danger'>Preencha o nome da categoria.</div>";
    }
}

// Excluir categoria
if (isset($_GET['excluir'])) {
    $id_excluir = $_GET['excluir'];
    $stmt = $conn->prepare("DELETE FROM categorias WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $id_excluir, $id_usuario);
    $stmt->execute();
    echo "<script>alert('Categoria excluída com sucesso.'); window.location.href='index.php?page=categorias';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container mt-5">
    <h2>Gerenciar Categorias</h2>

    <form method="POST" class="mb-4 mt-3">
        <input type="hidden" name="id_categoria" value="<?= isset($id_editar) ? $id_editar : '' ?>">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome da Categoria</label>
            <input type="text" class="form-control" name="nome" id="nome" value="<?= isset($categoria_editar['nome']) ? htmlspecialchars($categoria_editar['nome']) : '' ?>" required>
        </div>
        <?php if (isset($id_editar)) : ?>
            <button type="submit" name="atualizar_categoria" class="btn btn-warning">Atualizar</button>
            <a href="index.php?page=categorias" class="btn btn-secondary">Cancelar</a>
        <?php else : ?>
            <button type="submit" name="nova_categoria" class="btn btn-primary">Adicionar</button>
        <?php endif; ?>
    </form>

    <h4>Lista de Categorias</h4>
    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->prepare("SELECT * FROM categorias WHERE id_usuario = ? ORDER BY nome");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $contador = 1;

            while ($cat = $result->fetch_assoc()) :
            ?>
                <tr>
                    <td><?= $contador++ ?></td>
                    <td><?= htmlspecialchars($cat['nome']) ?></td>
                    <td>
                        <a href="index.php?page=categorias&editar=<?= $cat['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="index.php?page=categorias&excluir=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta categoria?');">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/rodape.php'; ?>
