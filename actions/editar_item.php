<?php
require_once "../includes/proteger.php";
require_once "../includes/conexao.php";

$id = $_POST['id'] ?? 0;
$titulo = $_POST['titulo'] ?? '';
$autor = $_POST['autor'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$categoria_id = $_POST['categoria_id'] ?? '';
$id_usuario = $_SESSION['id_usuario'];
$capa_nova = '';

$sqlCheck = "SELECT capa FROM itens WHERE id = ? AND id_usuario = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("ii", $id, $id_usuario);
$stmtCheck->execute();
$res = $stmtCheck->get_result();
$item = $res->fetch_assoc();

if (!$item) {
    echo "<script>alert('Item não encontrado.'); location.href='../home.php';</script>";
    exit;
}

// Upload nova capa
if (!empty($_FILES['capa']['name'])) {
    $capa_nova = uniqid() . "_" . $_FILES['capa']['name'];
    move_uploaded_file($_FILES['capa']['tmp_name'], "../uploads/" . $capa_nova);
} else {
    $capa_nova = $item['capa'];
}

$sql = "UPDATE itens SET titulo=?, autor=?, descricao=?, tipo=?, categoria_id=?, capa=? 
        WHERE id=? AND id_usuario=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssii", $titulo, $autor, $descricao, $tipo, $categoria_id, $capa_nova, $id, $id_usuario);

if ($stmt->execute()) {
    header("Location: ../home.php");
} else {
    echo "<script>alert('Erro ao atualizar item.'); history.back();</script>";
}
