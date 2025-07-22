<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">Cadastro</h2>
    <form action="actions/cadastrar.php" method="POST" class="mx-auto mt-4" style="max-width: 400px;">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome completo</label>
            <input type="text" class="form-control" name="nome" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" class="form-control" name="senha" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Cadastrar</button>
        <p class="text-center mt-3">
            <a href="login.php">Já tem conta? Faça login</a>
        </p>
    </form>
</div>

</body>
</html>
