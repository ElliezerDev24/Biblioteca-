<?php
require_once "../includes/proteger.php";
require_once "../includes/conexao.php";

if (!isset($_POST['id_operacao'], $_POST['tipo'], $_POST['destinatario'])) {
    $_SESSION['mensagem'] = "Dados incompletos.";
    header("Location: ../operacoes.php");
    exit;
}

$id_operacao = $_POST['id_operacao'];
$tipo = $_POST['tipo'];
$destinatario = $_POST['destinatario'];
$id_usuario = $_SESSION['id_usuario'];

// Atualiza a operação principal
$sql = "UPDATE operacoes SET tipo = ?, destinatario = ? WHERE id = ? AND id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $tipo, $destinatario, $id_operacao, $id_usuario);
$stmt->execute();

// Atualiza os itens da operação
if ($tipo === 'emprestimo' && isset($_POST['dias'])) {
    foreach ($_POST['dias'] as $id_item => $dias) {
        $dias = (int)$dias;
        $stmt = $conn->prepare("UPDATE operacao_itens SET dias = ?, valor = NULL WHERE id = ? AND id_operacao = ?");
        $stmt->bind_param("iii", $dias, $id_item, $id_operacao);
        $stmt->execute();
    }
} elseif ($tipo === 'venda' && isset($_POST['valor'])) {
    foreach ($_POST['valor'] as $id_item => $valor) {
        $valor = (float)$valor;
        $stmt = $conn->prepare("UPDATE operacao_itens SET valor = ?, dias = NULL WHERE id = ? AND id_operacao = ?");
        $stmt->bind_param("dii", $valor, $id_item, $id_operacao);
        $stmt->execute();
    }
}

$_SESSION['mensagem'] = "Operação atualizada com sucesso.";
header("Location: ../operacoes.php");
exit;
