<?php
require_once "../includes/proteger.php";
require_once "../includes/conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../carrinho.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'] ?? null;
$destinatario = $_POST['destinatario'] ?? '';
$tipo_operacao = $_POST['tipo_operacao'] ?? '';
$itens = $_POST['itens'] ?? [];

if (!$id_usuario || !$destinatario || !$tipo_operacao || empty($itens)) {
    $_SESSION['mensagem'] = "Dados incompletos.";
    header("Location: ../carrinho.php");
    exit();
}

// Iniciar transação
$conn->begin_transaction();

try {
    // Inserir a operação principal
    $sql = "INSERT INTO operacoes (id_usuario, tipo, destinatario, data_operacao) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $id_usuario, $tipo_operacao, $destinatario);
    $stmt->execute();
    $id_operacao = $stmt->insert_id;
    $stmt->close();

    // Inserir os itens da operação
    $sqlItem = "INSERT INTO operacao_itens (id_operacao, id_item, dias, valor) VALUES (?, ?, ?, ?)";
    $stmtItem = $conn->prepare($sqlItem);

    foreach ($itens as $id_item => $dados) {
        if (!isset($dados['selecionado'])) continue;

        $dias = isset($dados['dias']) ? (int)$dados['dias'] : null;
        $valor = isset($dados['valor']) ? floatval($dados['valor']) : null;

        $stmtItem->bind_param("iiid", $id_operacao, $id_item, $dias, $valor);
        $stmtItem->execute();

        // Remover do carrinho
        $sqlDel = "DELETE FROM carrinho WHERE id_usuario = ? AND id_item = ?";
        $stmtDel = $conn->prepare($sqlDel);
        $stmtDel->bind_param("ii", $id_usuario, $id_item);
        $stmtDel->execute();
        $stmtDel->close();
    }

    $stmtItem->close();

    $conn->commit();
    $_SESSION['mensagem'] = "Operação salva com sucesso!";
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['mensagem'] = "Erro ao salvar operação: " . $e->getMessage();
}

header("Location: ../operacoes.php");
exit();
