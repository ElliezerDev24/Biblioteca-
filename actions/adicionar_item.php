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
if (!empty($_FILES['capa']['name']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
    $nome_original = basename($_FILES['capa']['name']);
    $nome_sanitizado = preg_replace("/[^a-zA-Z0-9._-]/", "", $nome_original); // Remove caracteres especiais
    $capa_nome = "uploads/" . uniqid() . "_" . $nome_sanitizado;

    $caminho_destino = "../" . $capa_nome;

    // Move o arquivo para a pasta uploads/
    if (!move_uploaded_file($_FILES['capa']['tmp_name'], $caminho_destino)) {
        echo "<script>alert('Erro ao fazer upload da imagem.'); history.back();</script>";
        exit;
    }
}

$sql = "INSERT INTO itens (titulo, autor, descricao, tipo, categoria_id, capa, id_usuario) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $titulo, $autor, $descricao, $tipo, $categoria_id, $capa_nome, $id_usuario);

if ($stmt->execute()) {
    header("Location: ../home.php");
    exit;
} else {
    echo "<script>alert('Erro ao salvar item.'); history.back();</script>";
    exit;
}
