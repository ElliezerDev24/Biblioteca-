<?php
require_once "../includes/proteger.php";
require_once "../includes/conexao.php";

$id_usuario = $_SESSION['id_usuario'];
$id_item = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_item > 0) {
    $sql = "DELETE FROM carrinho WHERE id_usuario = ? AND id_item = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_item);
    $stmt->execute();
}

header("Location: ../carrinho.php");
exit();
