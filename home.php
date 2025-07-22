<?php
require_once "includes/proteger.php";
require_once "includes/conexao.php";
require_once "includes/cabecalho.php";

$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT * FROM itens WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Assets/css/style.css">
</head>


<div class="container mt-4">
    <h2>Meus Itens</h2>
    <div class="row">
        <?php while ($item = $resultado->fetch_assoc()) { ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <?php if (!empty($item['capa'])): ?>
                        <img src="uploads/<?= htmlspecialchars($item['capa']) ?>" class="card-img-top" alt="Capa">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($item['titulo']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($item['autor']) ?></p>
                        <a href="detalhes.php?id=<?= $item['id'] ?>" class="btn btn-primary btn-sm">Detalhes</a>
                        <a href="editar_item.php?id=<?= $item['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="actions/remover_item.php?id=<?= $item['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remover este item?')">Remover</a>
                        
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php require_once "includes/rodape.php"; ?>


