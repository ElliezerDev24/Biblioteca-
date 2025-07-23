<?php
require_once "../includes/proteger.php";
require_once "../includes/conexao.php";

$id_operacao = $_GET['id'] ?? null;

if (!$id_operacao) {
    $_SESSION['mensagem'] = "ID inválido.";
    header("Location: ../operacoes.php");
    exit();
}

$conn->begin_transaction();

try {
    // Deleta os itens da operação
    $sql = "DELETE FROM operacao_itens WHERE id_operacao = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_operacao);
    $stmt->execute();
    $stmt->close();

    // Deleta a operação
    $sql = "DELETE FROM operacoes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_operacao);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    $_SESSION['mensagem'] = "Operação cancelada com sucesso!";
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['mensagem'] = "Erro ao cancelar operação.";
}

header("Location: ../operacoes.php");
exit();
