<?php
include '../includes/conexao.php';
include '../includes/cabecalho.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php?page=login");
    exit();
}

if (!isset($_GET['id'])) {
    echo "<div class='container mt-4 alert alert-danger'>ID da operação não informado.</div>";
    include '../includes/rodape.php';
    exit();
}

$id_operacao = $_GET['id'];
$id_usuario = $_SESSION['id_usuario'];

// Verifica se a operação pertence ao usuário
$sql_verifica = "SELECT * FROM operacoes WHERE id = ? AND id_usuario = ?";
$stmt = $conn->prepare($sql_verifica);
$stmt->bind_param("ii", $id_operacao, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='container mt-4 alert alert-warning'>Operação não encontrada ou acesso negado.</div>";
    include '../includes/rodape.php';
    exit();
}

// Deleta os itens vinculados à operação
$sql_delete_itens = "DELETE FROM itens_operacao WHERE id_operacao = ?";
$stmt_itens = $conn->prepare($sql_delete_itens);
$stmt_itens->bind_param("i", $id_operacao);
$stmt_itens->execute();

// Deleta a operação em si
$sql_delete_operacao = "DELETE FROM operacoes WHERE id = ?";
$stmt_operacao = $conn->prepare($sql_delete_operacao);
$stmt_operacao->bind_param("i", $id_operacao);
$stmt_operacao->execute();

echo "<script>alert('Operação cancelada com sucesso.'); window.location.href='../index.php?page=operacoes';</script>";
exit();
?>
