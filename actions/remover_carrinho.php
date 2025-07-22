<?php
session_start();
include '../includes/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../index.php?page=login');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_item = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_item > 0) {
    $stmt = $conn->prepare("DELETE FROM carrinho WHERE id_usuario = ? AND id_item = ?");
    $stmt->bind_param("ii", $id_usuario, $id_item);
    $stmt->execute();
}

header('Location: ../index.php?page=carrinho');
exit;
