<?php
require_once "../includes/proteger.php";
require_once "../includes/conexao.php";

if (isset($_GET['id'])) {
    $id_item = (int) $_GET['id'];
    $id_usuario = $_SESSION['id_usuario'];

    // Verificar se o item já está no carrinho
    $sql = "SELECT * FROM carrinho WHERE id_usuario = ? AND id_item = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_item);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Já está no carrinho, então remove
        $sql = "DELETE FROM carrinho WHERE id_usuario = ? AND id_item = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_item);
        $stmt->execute();
    } else {
        // Adiciona ao carrinho
        $sql = "INSERT INTO carrinho (id_usuario, id_item) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_item);
        $stmt->execute();
    }

    header("Location: ../index.php?page=home");
    exit();
} else {
    header("Location: ../index.php?page=home");
    exit();
}
