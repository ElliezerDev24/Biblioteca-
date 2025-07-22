<?php
// arquivo responsável por proteger as páginas e verificar se o usuário está logado para não permitir acesso não autorizado, direcionando para a página de login se necessário e garantindo que a sessão esteja ativa não permitindo que dê erro de ignorar sessão já que o logout também destrói a sessão.
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}
?>
