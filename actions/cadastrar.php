<?php
require_once "../includes/conexao.php";

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $email, $senhaCriptografada);

if ($stmt->execute()) {
    echo "<script>alert('Cadastro realizado com sucesso!'); location.href='../login.php';</script>";
} else {
    echo "<script>alert('Erro ao cadastrar. Tente outro e-mail.'); location.href='../cadastro.php';</script>";
}
