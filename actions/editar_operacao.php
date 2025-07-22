<?php
include 'includes/conexao.php';
include 'includes/cabecalho.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php?page=login");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Verifica se o id da operação foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='container mt-4 alert alert-danger'>ID da operação não informado.</div>";
    include 'includes/rodape.php';
    exit();
}

$id_operacao = $_GET['id'];

// Busca dados da operação e verifica se pertence ao usuário
$stmt = $conn->prepare("SELECT * FROM operacoes WHERE id = ? AND id_usuario = ?");
$stmt->bind_param("ii", $id_operacao, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='container mt-4 alert alert-warning'>Operação não encontrada ou acesso negado.</div>";
    include 'includes/rodape.php';
    exit();
}

$operacao = $result->fetch_assoc();

// Se o formulário for enviado para atualizar operação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar_operacao'])) {
    $tipo = $_POST['tipo'] ?? '';
    $destinatario = trim($_POST['destinatario'] ?? '');

    if (empty($tipo) || empty($destinatario)) {
        echo "<div class='container mt-4 alert alert-danger'>Por favor, preencha todos os campos.</div>";
    } else {
        $stmt_update = $conn->prepare("UPDATE operacoes SET tipo = ?, destinatario = ? WHERE id = ? AND id_usuario = ?");
        $stmt_update->bind_param("ssii", $tipo, $destinatario, $id_operacao, $id_usuario);
        if ($stmt_update->execute()) {
            echo "<script>alert('Operação atualizada com sucesso.'); window.location.href='index.php?page=operacoes';</script>";
            exit();
        } else {
            echo "<div class='container mt-4 alert alert-danger'>Erro ao atualizar operação.</div>";
        }
    }
}

// Busca itens vinculados a essa operação
$stmt_itens = $conn->prepare(
    "SELECT io.id AS io_id, i.titulo, io.valor 
    FROM itens_operacao io
    JOIN itens i ON io.id_item = i.id
    WHERE io.id_operacao = ?"
);
$stmt_itens->bind_param("i", $id_operacao);
$stmt_itens->execute();
$result_itens = $stmt_itens->get_result();
?>

<div class="container mt-5">
    <h3>Editar Operação #<?= $id_operacao ?></h3>

    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de Operação</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="">Selecione</option>
                <option value="venda" <?= $operacao['tipo'] === 'venda' ? 'selected' : '' ?>>Venda</option>
                <option value="emprestimo" <?= $operacao['tipo'] === 'emprestimo' ? 'selected' : '' ?>>Empréstimo</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="destinatario" class="form-label">Destinatário</label>
            <input type="text" name="destinatario" id="destinatario" class="form-control" value="<?= htmlspecialchars($operacao['destinatario']) ?>" required>
        </div>

        <button type="submit" name="atualizar_operacao" class="btn btn-primary">Salvar Alterações</button>
        <a href="index.php?page=operacoes" class="btn btn-secondary ms-2">Cancelar</a>
    </form>

    <h4>Itens da Operação</h4>
    <?php if ($result_itens->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Título</th>
                    <th>Valor (R$)</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $result_itens->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($item['titulo']) ?></td>
                    <td><?= number_format($item['valor'], 2, ',', '.') ?></td>
                    <td>
                        <a href="actions/remover_item_operacao.php?id=<?= $item['io_id'] ?>&operacao=<?= $id_operacao ?>" class="btn btn-sm btn-danger" onclick="return confirm('Remover este item da operação?');">Remover</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Nenhum item vinculado a esta operação.</div>
    <?php endif; ?>
</div>

<?php include 'includes/rodape.php'; ?>
