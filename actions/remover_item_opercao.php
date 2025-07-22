<?php
include '../includes/conexao.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php?page=login");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_io = $_GET['id'] ?? null; // id da linha itens_operacao
$id_operacao = $_GET['operacao'] ?? null;

if (!$id_io || !$id_operacao) {
    echo "<script>alert('Parâmetros inválidos.'); window.history.back();</script>";
    exit();
}

// Verifica se a operação pertence ao usuário
$stmt = $conn->prepare("SELECT id FROM operacoes WHERE id = ? AND id_usuario = ?");
$stmt->bind_param("ii", $id_operacao, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "<script>alert('Operação não encontrada ou acesso negado.'); window.history.back();</script>";
    exit();
}

// Remove o item da operação
$stmt_delete = $conn->prepare("DELETE FROM itens_operacao WHERE id = ? AND id_operacao = ?");
$stmt_delete->bind_param("ii", $id_io, $id_operacao);
$stmt_delete->execute();

if ($stmt_delete->affected_rows > 0) {
    // Atualizar o valor_total da operação somando os itens restantes
    $stmt_sum = $conn->prepare("SELECT SUM(valor) as total FROM itens_operacao WHERE id_operacao = ?");
    $stmt_sum->bind_param("i", $id_operacao);
    $stmt_sum->execute();
    $res_sum = $stmt_sum->get_result();
    $row = $res_sum->fetch_assoc();
    $novo_valor_total = $row['total'] ?? 0;

    $stmt_update = $conn->prepare("UPDATE operacoes SET valor_total = ? WHERE id = ?");
    $stmt_update->bind_param("di", $novo_valor_total, $id_operacao);
    $stmt_update->execute();

    echo "<script>alert('Item removido com sucesso.'); window.location.href='../index.php?page=editar_operacao&id=$id_operacao';</script>";
} else {
    echo "<script>alert('Falha ao remover o item.'); window.history.back();</script>";
}
