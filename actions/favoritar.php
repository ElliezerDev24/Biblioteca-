<?php
require_once "../includes/proteger.php";
require_once "../includes/conexao.php";

if (!isset($_GET['id'])) {
    header("Location: ../index.php?page=home");
    exit;
}

$id_item = intval($_GET['id']);
$id_usuario = $_SESSION['id_usuario'];

// Verifica se já está favoritado
$sql = "SELECT * FROM favoritos WHERE id_usuario = ? AND id_item = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_usuario, $id_item);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    // Já está favoritado, vamos remover (desfavoritar)
    $sql = "DELETE FROM favoritos WHERE id_usuario = ? AND id_item = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_item);
    $stmt->execute();
} else {
    // Não está favoritado, vamos adicionar
    $sql = "INSERT INTO favoritos (id_usuario, id_item) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_item);
    $stmt->execute();
}

// Redireciona de volta à página anterior
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
