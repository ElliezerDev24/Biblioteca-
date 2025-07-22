<?php
include '../includes/conexao.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php?page=login");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php?page=carrinho");
    exit();
}

// Recebe dados do formulário
$tipo_operacao = $_POST['tipo_operacao'] ?? '';
$destinatario = trim($_POST['destinatario'] ?? '');
$itens_selecionados = $_POST['itens_selecionados'] ?? [];

if (empty($itens_selecionados) || $tipo_operacao === '' || $destinatario === '') {
    echo "<script>alert('Por favor, preencha todos os campos e selecione ao menos um item.'); window.history.back();</script>";
    exit();
}

// Buscar valores dos itens selecionados
$placeholders = implode(',', array_fill(0, count($itens_selecionados), '?'));
$tipos = str_repeat('i', count($itens_selecionados));

$sql_valores = "SELECT id, valor FROM itens WHERE id IN ($placeholders)";
$stmt_valores = $conn->prepare($sql_valores);

$stmt_valores->bind_param($tipos, ...$itens_selecionados);
$stmt_valores->execute();
$result_valores = $stmt_valores->get_result();

$valor_total = 0;
$itens_info = [];
while ($row = $result_valores->fetch_assoc()) {
    $valor_total += $row['valor'];
    $itens_info[] = $row;
}

// Inserir na tabela operacoes
$sql_operacao = "INSERT INTO operacoes (id_usuario, tipo, destinatario, valor_total) VALUES (?, ?, ?, ?)";
$stmt_operacao = $conn->prepare($sql_operacao);
$stmt_operacao->bind_param("issd", $id_usuario, $tipo_operacao, $destinatario, $valor_total);

if (!$stmt_operacao->execute()) {
    echo "<script>alert('Erro ao registrar operação.'); window.history.back();</script>";
    exit();
}

$id_operacao = $stmt_operacao->insert_id;

// Inserir os itens na itens_operacao
$sql_item_op = "INSERT INTO itens_operacao (id_operacao, id_item, valor) VALUES (?, ?, ?)";
$stmt_item_op = $conn->prepare($sql_item_op);

foreach ($itens_info as $item) {
    $stmt_item_op->bind_param("iid", $id_operacao, $item['id'], $item['valor']);
    $stmt_item_op->execute();
}

// Remover itens do carrinho
$sql_remover = "DELETE FROM carrinho WHERE usuario_id = ? AND item_id IN ($placeholders)";
$stmt_remover = $conn->prepare($sql_remover);
$stmt_remover->bind_param('i' . $tipos, $id_usuario, ...$itens_selecionados);
$stmt_remover->execute();

echo "<script>alert('Operação registrada com sucesso!'); window.location.href='../index.php?page=operacoes';</script>";
exit();
