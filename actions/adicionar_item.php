<?php
require_once "../includes/proteger.php";
require_once "../includes/conexao.php";

$titulo = $_POST['titulo'] ?? '';
$autor = $_POST['autor'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$categoria_id = $_POST['categoria_id'] ?? '';
$id_usuario = $_SESSION['id_usuario'];
$capa_nome = '';

// Upload da capa
if (!empty($_FILES['capa']['name'])) {
    $capa_nome = uniqid() . "_" . $_FILES['capa']['name'];
    move_uploaded_file($_FILES['capa']['tmp_name'], "../uploads/" . $capa_nome);
}

$sql = "INSERT INTO itens (titulo, autor, descricao, tipo, categoria_id, capa, id_usuario) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $titulo, $autor, $descricao, $tipo, $categoria_id, $capa_nome, $id_usuario);

if ($stmt->execute()) {
    header("Location: ../home.php");
} else {
    echo "<script>alert('Erro ao salvar item.'); history.back();</script>";
}
