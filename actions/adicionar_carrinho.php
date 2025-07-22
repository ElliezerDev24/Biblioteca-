<?php
require_once "../includes/proteger.php";
require_once "../includes/conexao.php";

$id_usuario = $_SESSION['id_usuario'];
$id_item = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_item <= 0) {
    header("Location: ../index.php?page=home");
    exit();
}

// Verifica se o item já está no carrinho
$sql = "SELECT id FROM carrinho WHERE id_usuario = ? AND id_item = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_usuario, $id_item);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Item já está no carrinho - remover
    $sql = "DELETE FROM carrinho WHERE id_usuario = ? AND id_item = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_item);
    $stmt->execute();
} else {
    // Item não está no carrinho - adicionar
    $sql = "INSERT INTO carrinho (id_usuario, id_item) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_item);
    $stmt->execute();
}

header("Location: ../index.php?page=home");
exit();
