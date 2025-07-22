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
    // Verifica se já está no carrinho
    $check = $conn->prepare("SELECT 1 FROM carrinho WHERE id_usuario = ? AND id_item = ?");
    $check->bind_param("ii", $id_usuario, $id_item);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO carrinho (id_usuario, id_item) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_usuario, $id_item);
        $stmt->execute();
    }
}

header('Location: ../index.php?page=home');
exit;
