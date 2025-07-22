<?php
require_once "includes/proteger.php";
require_once "includes/conexao.php";
require_once "includes/cabecalho.php";

// Buscar favoritos do usuário
$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT itens.* FROM favoritos 
        JOIN itens ON favoritos.id_item = itens.id 
        WHERE favoritos.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Home - Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Assets/css/style.css">
</head>


<div class="container mt-4">
    <h2 class="mb-4">Meus Favoritos</h2>

    <div class="row">
        <?php while ($item = $resultado->fetch_assoc()) { ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php
$caminho_capa = 'uploads/' . $item['capa'];
if (!empty($item['capa']) && file_exists($caminho_capa)): ?>
    <img src="<?php echo htmlspecialchars($caminho_capa); ?>" class="card-img-top" alt="Capa">
<?php else: ?>
    <img src="img/sem-capa.png" class="card-img-top" alt="Sem capa">
<?php endif; ?>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($item['titulo']); ?></h5>
                        <p class="card-text">
                            <strong>Autor:</strong> <?php echo htmlspecialchars($item['autor']); ?><br>
                            <strong>Tipo:</strong> <?php echo htmlspecialchars($item['tipo']); ?>
                        </p>
                        <div class="mt-auto">
                            <!-- Botão de desfavoritar -->
                            <a href="actions/favoritar.php?id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm w-100 mb-1">
                                Desfavoritar
                            </a>
                            <!-- Nenhum outro botão aqui -->
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php if ($resultado->num_rows === 0): ?>
        <p class="text-muted">Nenhum item favoritado.</p>
    <?php endif; ?>
</div>

<?php require_once "includes/rodape.php"; ?>
