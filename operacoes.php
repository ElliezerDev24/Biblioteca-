<?php
include 'includes/conexao.php';
include 'includes/cabecalho.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php?page=login");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Busca as operações do usuário
$sql = "SELECT id, tipo, destinatario, valor_total, data_operacao FROM operacoes WHERE id_usuario = ? ORDER BY data_operacao DESC";
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

<div class="container mt-5">
    <h3>Minhas Operações</h3>

    <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Destinatário</th>
                <th>Valor Total (R$)</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($op = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $op['id'] ?></td>
                <td><?= ucfirst($op['tipo']) ?></td>
                <td><?= htmlspecialchars($op['destinatario']) ?></td>
                <td><?= number_format($op['valor_total'], 2, ',', '.') ?></td>
                <td><?= date('d/m/Y H:i', strtotime($op['data_operacao'])) ?></td>
                <td>
                    <a href="index.php?page=editar_operacao&id=<?= $op['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="actions/cancelar_operacao.php?id=<?= $op['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja cancelar esta operação?');">Cancelar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="alert alert-info mt-4">Nenhuma operação registrada.</div>
    <?php endif; ?>
</div>

<?php include 'includes/rodape.php'; ?>
