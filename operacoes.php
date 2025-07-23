<?php
require_once "includes/proteger.php";
require_once "includes/conexao.php";
require_once "includes/cabecalho.php";

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT * FROM operacoes WHERE id_usuario = ? ORDER BY data_operacao DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<h2>Minhas Operações</h2>

<?php if (isset($_SESSION['mensagem'])): ?>
    <div class="alert alert-info"><?= $_SESSION['mensagem'] ?></div>
    <?php unset($_SESSION['mensagem']); ?>
<?php endif; ?>

<?php while ($op = $result->fetch_assoc()): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"><?= ucfirst($op['tipo']) ?> - <?= htmlspecialchars($op['destinatario']) ?></h5>
            <p class="card-text"><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($op['data_operacao'])) ?></p>

            <ul>
            <?php
                $sqlItens = "SELECT oi.*, i.titulo 
                             FROM operacao_itens oi
                             JOIN itens i ON oi.id_item = i.id
                             WHERE oi.id_operacao = ?";
                $stmtItens = $conn->prepare($sqlItens);
                $stmtItens->bind_param("i", $op['id']);
                $stmtItens->execute();
                $resItens = $stmtItens->get_result();
                while ($item = $resItens->fetch_assoc()):
            ?>
                <li>
                    <?= htmlspecialchars($item['titulo']) ?>
                    <?php if ($op['tipo'] === 'emprestimo'): ?>
                        - <?= $item['dias'] ?> dias
                    <?php else: ?>
                        - R$ <?= number_format($item['valor'], 2, ',', '.') ?>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
            </ul>

            <a href="actions/excluir_operacao.php?id=<?= $op['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Cancelar operação?')">Cancelar</a>

            <a href="editar_operacao.php?id=<?= $op['id'] ?>" class="btn btn-warning btn-sm">Editar</a>

        </div>
    </div>
<?php endwhile; ?>
<?php require_once "includes/rodape.php"; ?>
