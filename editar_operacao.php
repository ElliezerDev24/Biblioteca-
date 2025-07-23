<?php
require_once "includes/proteger.php";
require_once "includes/conexao.php";
require_once "includes/cabecalho.php";

if (!isset($_GET['id'])) {
    $_SESSION['mensagem'] = "Operação não informada.";
    header("Location: operacoes.php");
    exit;
}

$id_operacao = $_GET['id'];
$id_usuario = $_SESSION['id_usuario'];

// Buscar dados da operação
$sql = "SELECT * FROM operacoes WHERE id = ? AND id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_operacao, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$operacao = $result->fetch_assoc();

if (!$operacao) {
    $_SESSION['mensagem'] = "Operação não encontrada.";
    header("Location: operacoes.php");
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

<h2>Editar Operação</h2>

<form action="actions/salvar_edicao_operacao.php" method="post">
    <input type="hidden" name="id_operacao" value="<?= $operacao['id'] ?>">

    <div class="mb-3">
        <label for="tipo" class="form-label">Tipo</label>
        <select name="tipo" id="tipo" class="form-select" required>
            <option value="emprestimo" <?= $operacao['tipo'] == 'emprestimo' ? 'selected' : '' ?>>Empréstimo</option>
            <option value="venda" <?= $operacao['tipo'] == 'venda' ? 'selected' : '' ?>>Venda</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="destinatario" class="form-label">Destinatário</label>
        <input type="text" name="destinatario" id="destinatario" class="form-control" value="<?= htmlspecialchars($operacao['destinatario']) ?>" required>
    </div>

    <h5>Itens da Operação</h5>

    <?php
    $sqlItens = "SELECT oi.*, i.titulo 
                 FROM operacao_itens oi 
                 JOIN itens i ON oi.id_item = i.id 
                 WHERE oi.id_operacao = ?";
    $stmtItens = $conn->prepare($sqlItens);
    $stmtItens->bind_param("i", $id_operacao);
    $stmtItens->execute();
    $resItens = $stmtItens->get_result();
    ?>

    <?php while ($item = $resItens->fetch_assoc()): ?>
        <div class="mb-3">
            <label class="form-label"><?= htmlspecialchars($item['titulo']) ?></label>
            <?php if ($operacao['tipo'] === 'emprestimo'): ?>
                <input type="number" name="dias[<?= $item['id'] ?>]" class="form-control" value="<?= $item['dias'] ?>" placeholder="Dias de empréstimo">
            <?php else: ?>
                <input type="number" name="valor[<?= $item['id'] ?>]" class="form-control" step="0.01" value="<?= $item['valor'] ?>" placeholder="Valor da venda">
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    <a href="operacoes.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once "includes/rodape.php"; ?>
