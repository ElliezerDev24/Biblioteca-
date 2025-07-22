<?php
require_once "../includes/proteger.php";
require_once "../includes/conexao.php";

$tipo = $_POST['tipo_operacao'] ?? '';
$destinatario = $_POST['destinatario'] ?? '';
$id_usuario = $_SESSION['id_usuario'];
$itens = $_POST['itens'] ?? [];
$itens_venda = $_POST['itens_marcados'] ?? [];
$data = date("Y-m-d");

foreach ($itens as $item_id) {
    $valor = in_array($item_id, $itens_venda) ? 10.00 : 0.00;

    $sql = "INSERT INTO operacoes (usuario_id, item_id, tipo, destinatario, valor, data_operacao) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissds", $id_usuario, $item_id, $tipo, $destinatario, $valor, $data);
    $stmt->execute();

    // Remover do carrinho após operação
    $del = $conn->prepare("DELETE FROM carrinho WHERE usuario_id = ? AND item_id = ?");
    $del->bind_param("ii", $id_usuario, $item_id);
    $del->execute();
}

echo "<script>alert('Operação realizada com sucesso.'); location.href='../operacoes.php';</script>";
