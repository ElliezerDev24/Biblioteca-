<?php
session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['id_usuario'])) {
    // Conecta ao banco de dados
    header("Location: home.php");
    exit;
    // Se o usuário não estiver logado, redireciona para a página de login
} else {
    header("Location: login.php");
    exit;
    // Se o usuário estiver logado, redireciona para a página inicial
}
?>
