<?php
require_once "../includes/proteger.php";
require_once "../includes/conexao.php";

$id = $_GET['id'] ?? 0;
$id_usuario = $_SESSION['id_usuario'];

// Buscar capa para remover
$sql = "SELECT capa FROM itens WHERE id = ? AND id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $id_usuario);
$stmt->execute();
$res = $stmt->get_result();
$item = $res->fetch_assoc();

if ($item) {
    if (!empty($item['capa']) && file_exists("../uploads/" . $item['capa'])) {
        unlink("../uploads/" . $item['capa']);
    }

    $sqlDelete = "DELETE FROM itens WHERE id = ? AND id_usuario = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("ii", $id, $id_usuario);
    $stmtDelete->execute();
}

header("Location: ../home.php");
