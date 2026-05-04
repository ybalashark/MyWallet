<?php
include("autent.php");
include("funcoes.php");
verificarLogin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
        $nome  = trim($_POST['nome']);
        $valor = floatval($_POST['valor']);
        $tipo  = $_POST['tipo'];

        if ($nome !== "" && $valor > 0 && in_array($tipo, ['receita', 'despesa'])) {
            $_SESSION['transacoes'][] = [
                'nome'  => htmlspecialchars($nome),
                'valor' => $valor,
                'tipo'  => $tipo,
                'data'  => date('d/m/Y H:i')
            ];
        }
    }

    header("Location: index.php");
    exit();
}

$transacoes    = $_SESSION['transacoes'];
$totalReceitas = calcularReceitas($transacoes);
$totalDespesas = calcularDespesas($transacoes);
$saldo         = calcularSaldo($transacoes);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyWallet – Dashboard</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
        }

        nav {
            background: #1a1a2e;
            color: white;
            padding: 14px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav .marca { font-size: 1.1rem; font-weight: bold; }

        nav .usuario { font-size: 0.88rem; margin-right: 14px; }

        nav a.btn-sair {
            background: #dc3545;
            color: white;
            text-decoration: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: bold;
        }

        nav a.btn-sair:hover { background: #b02a37; }

        .conteudo { padding: 28px; }

        .cards {
            display: flex;
            gap: 18px;
            margin-bottom: 24px;
        }

        .card {
            flex: 1;
            background: white;
            padding: 22px 20px;
            border-radius: 10px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.09);
        }

        .card.receita { border-left: 4px solid #198754; }
        .card.despesa { border-left: 4px solid #dc3545; }
        .card.saldo   { background: #0d6efd; }

        .card .rotulo {
            font-size: 0.82rem;
            color: #666;
            margin-bottom: 8px;
        }

        .card.saldo .rotulo { color: #cce5ff; }

        .card .valor { font-size: 1.55rem; font-weight: bold; }
        .card.receita .valor { color: #198754; }
        .card.despesa .valor { color: #dc3545; }
        .card.saldo   .valor { color: white; }

        .secao-form {
            background: white;
            padding: 22px 20px;
            border-radius: 10px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.09);
            margin-bottom: 18px;
        }

        .secao-form h3 {
            font-size: 0.95rem;
            margin-bottom: 16px;
            color: #333;
        }

        .form-linha {
            display: flex;
            gap: 12px;
            align-items: flex-end;
        }

        .form-grupo { display: flex; flex-direction: column; flex: 1; }

        .form-grupo label {
            font-size: 0.78rem;
            color: #888;
            margin-bottom: 5px;
        }

        .form-grupo input,
        .form-grupo select {
            padding: 9px 11px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 0.92rem;
            outline: none;
        }

        .form-grupo input:focus,
        .form-grupo select:focus { border-color: #0d6efd; }

        .form-grupo.tipo   { max-width: 150px; }
        .form-grupo.valor  { max-width: 170px; }

        .btn-adicionar {
            padding: 9px 22px;
            background: #1a1a2e;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.92rem;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-adicionar:hover { background: #333; }

        .link-historico {
            display: block;
            text-align: center;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 8px;
            text-decoration: none;
            color: #444;
            background: white;
            font-size: 0.92rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.07);
        }

        .link-historico:hover { background: #f5f5f5; }
    </style>
</head>
<body>

<nav>
    <span class="marca"> MyWallet</span>
    <div style="display:flex; align-items:center;">
        <span class="usuario">Olá, <?= htmlspecialchars($_SESSION['usuario']) ?> ! </span>
        <a href="logout.php" class="btn-sair">Sair</a>
    </div>
</nav>

<div class="conteudo">

    <div class="cards">
        <div class="card receita">
            <div class="rotulo">Total Receitas</div>
            <div class="valor"><?= formatarMoeda($totalReceitas) ?></div>
        </div>
        <div class="card despesa">
            <div class="rotulo">Total Despesas</div>
            <div class="valor"><?= formatarMoeda($totalDespesas) ?></div>
        </div>
        <div class="card saldo">
            <div class="rotulo">Saldo Disponível</div>
            <div class="valor"><?= formatarMoeda($saldo) ?></div>
        </div>
    </div>

    <div class="secao-form">
        <h3>Nova Transação</h3>
        <form method="POST">
            <input type="hidden" name="acao" value="adicionar">
            <div class="form-linha">
                <div class="form-grupo">
                    <label>Descrição</label>
                    <input type="text" name="nome" placeholder="Ex: Salário, Aluguel..." required>
                </div>
                <div class="form-grupo valor">
                    <label>Valor</label>
                    <input type="number" name="valor" step="0.01" min="0.01" placeholder="0,00" required>
                </div>
                <div class="form-grupo tipo">
                    <label>Tipo</label>
                    <select name="tipo">
                        <option value="receita">Receita</option>
                        <option value="despesa">Despesa</option>
                    </select>
                </div>
                <button type="submit" class="btn-adicionar">Adicionar</button>
            </div>
        </form>
    </div>

    <a href="historico.php" class="link-historico">Ver Detalhes do Histórico</a>

</div>

</body>
</html>
