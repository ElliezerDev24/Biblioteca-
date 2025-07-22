<?php
require_once "includes/proteger.php";
require_once "includes/conexao.php";
require_once "includes/cabecalho.php";

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT itens.*, carrinho.id AS id_carrinho
        FROM carrinho
        INNER JOIN itens ON carrinho.id_item = itens.id
        WHERE carrinho.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$itens = [];
while ($row = $result->fetch_assoc()) {
    $itens[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho - Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<h2 class="mb-4">Carrinho de Itens</h2>

<form method="POST" action="">
    <div class="mb-3">
        <label class="form-label">Destinatário</label>
        <input type="text" name="destinatario" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Tipo de operação</label><br>
        <input type="radio" name="tipo_operacao" value="emprestimo" checked onchange="alternarCampos()"> Empréstimo
        <input type="radio" name="tipo_operacao" value="venda" onchange="alternarCampos()"> Venda
    </div>

    <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="selecionarTodos">
        <label class="form-check-label" for="selecionarTodos">Marcar/Desmarcar todos</label>
    </div>

    <ul class="list-group mb-3">
        <?php foreach ($itens as $item): ?>
            <li class="list-group-item">
                <div class="form-check">
                    <input class="form-check-input item-checkbox" type="checkbox"
                           name="itens[<?= $item['id'] ?>][selecionado]"
                           value="1"
                           id="item<?= $item['id'] ?>"
                           onchange="atualizarVisibilidadeCampos(<?= $item['id'] ?>)">
                    <label class="form-check-label" for="item<?= $item['id'] ?>">
                        <?= htmlspecialchars($item['titulo']) ?> (<?= htmlspecialchars($item['tipo']) ?>)
                    </label>
                </div>

                <div class="row mt-2 campos-opcao d-none" id="campos_<?= $item['id'] ?>">
                    <div class="col-md-6 dias-field">
                        <label>Dias de empréstimo</label>
                        <input type="number" name="itens[<?= $item['id'] ?>][dias]"
                               class="form-control" min="1">
                    </div>
                    <div class="col-md-6 valor-field d-none">
                        <label>Valor de venda</label>
                        <input type="number"
                               name="itens[<?= $item['id'] ?>][valor]"
                               class="form-control valor-venda"
                               step="0.01" min="0"
                               oninput="calcularTotalVenda()">
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="mb-3">
        <strong>Total de venda: R$ <span id="totalVenda">0.00</span></strong>
    </div>

    <button type="submit" class="btn btn-primary">Confirmar Operação</button>
</form>

<script>
function alternarCampos() {
    const tipo = document.querySelector('input[name="tipo_operacao"]:checked').value;
    document.querySelectorAll('.campos-opcao').forEach(campo => {
        if (campo.closest('li').querySelector('.item-checkbox').checked) {
            campo.classList.remove('d-none');
        }
    });

    document.querySelectorAll('.dias-field').forEach(el => {
        el.classList.toggle('d-none', tipo !== 'emprestimo');
    });

    document.querySelectorAll('.valor-field').forEach(el => {
        el.classList.toggle('d-none', tipo !== 'venda');
    });

    calcularTotalVenda();
}

function atualizarVisibilidadeCampos(id) {
    const checkbox = document.getElementById('item' + id);
    const campos = document.getElementById('campos_' + id);

    if (checkbox.checked) {
        campos.classList.remove('d-none');
    } else {
        campos.classList.add('d-none');
    }

    alternarCampos();
}

function calcularTotalVenda() {
    let total = 0;
    const tipo = document.querySelector('input[name="tipo_operacao"]:checked').value;

    if (tipo !== 'venda') {
        document.getElementById('totalVenda').innerText = "0.00";
        return;
    }

    document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
        const id = checkbox.id.replace("item", "");
        const campo = document.querySelector(`[name="itens[${id}][valor]"]`);
        if (campo && campo.value) {
            total += parseFloat(campo.value) || 0;
        }
    });

    document.getElementById('totalVenda').innerText = total.toFixed(2);
}

document.getElementById('selecionarTodos').addEventListener('change', function () {
    const marcar = this.checked;
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.checked = marcar;
        atualizarVisibilidadeCampos(checkbox.id.replace("item", ""));
    });
});

document.addEventListener("DOMContentLoaded", () => {
    alternarCampos();
    calcularTotalVenda();
});
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['itens'])) {
    $destinatario = $_POST['destinatario'] ?? '';
    $tipo_operacao = $_POST['tipo_operacao'] ?? '';
    $itensSelecionados = $_POST['itens'];

    foreach ($itensSelecionados as $id_item => $dados) {
        if (!isset($dados['selecionado'])) continue;

        if ($tipo_operacao === 'emprestimo') {
            $dias = (int) ($dados['dias'] ?? 0);
            echo "Item $id_item será emprestado para $destinatario por $dias dias.<br>";
            // Inserção no banco aqui
        } elseif ($tipo_operacao === 'venda') {
            $valor = (float) ($dados['valor'] ?? 0);
            echo "Item $id_item será vendido para $destinatario por R$ " . number_format($valor, 2, ',', '.') . ".<br>";
            // Inserção no banco aqui
        }
    }
}

require_once "includes/rodape.php";
?>
</body>
</html>
