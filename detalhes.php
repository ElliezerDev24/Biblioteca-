<?php
require_once "includes/proteger.php";
require_once "includes/conexao.php";
require_once "includes/cabecalho.php";

$id = $_GET['id'] ?? 0;
$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT itens.*, categorias.nome AS categoria_nome 
        FROM itens 
        LEFT JOIN categorias ON itens.categoria_id = categorias.id 
        WHERE itens.id = ? AND itens.id_usuario = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $id_usuario);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

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
    <h2><?= htmlspecialchars($item['titulo']) ?></h2>
    <div class="row">
        <div class="col-md-4">
            <?php if (!empty($item['capa'])): ?>
                <img src="uploads/<?= htmlspecialchars($item['capa']) ?>" class="img-fluid rounded">
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <p><strong>Autor:</strong> <?= htmlspecialchars($item['autor']) ?></p>
            <p><strong>Tipo:</strong> <?= htmlspecialchars($item['tipo']) ?></p>
            <p><strong>Categoria:</strong> <?= htmlspecialchars($item['categoria_nome']) ?></p>
            <p><strong>Descrição:</strong><br><?= nl2br(htmlspecialchars($item['descricao'])) ?></p>
            <div class="card-body">
                <a href="home.php" class="btn btn-danger btn-sm">Voltar</a>
                </div>
        </div>
                
        
    </div>
</div>

<?php require_once "includes/rodape.php"; ?>
