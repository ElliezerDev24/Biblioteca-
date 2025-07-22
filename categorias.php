<?php

// Redireciona se não estiver logado
include 'includes/proteger.php';

// Conexão com o banco
$conn = new mysqli("localhost", "root", "", "biblioteca");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Exclusão de categoria, se o parâmetro 'excluir' for passado
$erro_exclusao = false;
if (isset($_GET['excluir'])) {
    $id_excluir = intval($_GET['excluir']);

    // Verifica se existem itens com essa categoria
    $verifica = $conn->prepare("SELECT COUNT(*) as total FROM itens WHERE categoria_id = ?");
    $verifica->bind_param("i", $id_excluir);
    $verifica->execute();
    $resultado = $verifica->get_result()->fetch_assoc();

    if ($resultado['total'] > 0) {
        $erro_exclusao = true;
    } else {
        // Pode excluir
        $excluir = $conn->prepare("DELETE FROM categorias WHERE id = ?");
        $excluir->bind_param("i", $id_excluir);
        $excluir->execute();
    }
}

// Inserção de nova categoria
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    if (!empty($nome)) {
        $stmt = $conn->prepare("INSERT INTO categorias (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);
        $stmt->execute();
    }
}

// Listar categorias
$categorias = [];
$result = $conn->query("SELECT * FROM categorias ORDER BY nome ASC");
while ($row = $result->fetch_assoc()) {
    $categorias[] = $row;
}
?>

<?php include "includes/cabecalho.php"; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>


<div class="container mt-5">
    <h2>Categorias</h2>

    <?php if ($erro_exclusao): ?>
        <div class="alert alert-danger">Não é possível excluir esta categoria, pois ela está sendo usada por um ou mais itens.</div>
    <?php endif; ?>

    <form method="post" class="row g-3 mt-3 mb-4">
        <div class="col-auto">
            <input type="text" name="nome" class="form-control" placeholder="Nova categoria" required>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </div>
    </form>

    <?php if (count($categorias) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th style="width: 150px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($categoria['nome']); ?></td>
                        <td>
                            <a href="categorias.php?excluir=<?php echo $categoria['id']; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma categoria cadastrada.</p>
    <?php endif; ?>
</div>

<?php include "includes/rodape.php"; ?>
