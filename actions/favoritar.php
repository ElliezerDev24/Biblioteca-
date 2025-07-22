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
    $stmt = $conn->prepare("INSERT INTO favoritos (id_usuario, id_item) VALUES (?, ?)
                            ON DUPLICATE KEY UPDATE id_item = id_item");
    $stmt->bind_param("ii", $id_usuario, $id_item);
    $stmt->execute();
}
if ($stmt->affected_rows > 0) {
    $_SESSION['mensagem'] = "Item adicionado aos favoritos com sucesso!";
} else {
    $_SESSION['mensagem'] = "Este item já está nos seus favoritos.";
}

header('Location: ../index.php?page=home');
exit;
?>
