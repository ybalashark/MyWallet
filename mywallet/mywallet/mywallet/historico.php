<?php
include ("autent.php");
include ("funcoes.php");
verificarLogin();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao'])) {

if ($_POST['acao'] === 'zerar') {
    $_SESSION['transacoes'] = [];
}

if ($_POST['acao'] === 'remover' && isset($_POST['indice'])) {
    $indice = intval($_POST['indice']);
    array_splice($_SESSION['transacoes'], $indice, 1);
}

header("Location: historico.php");
exit();
}

$transacoes  = $_SESSION['transacoes'];
$totalDespesas = calcularDespesas($transacoes);
?>

<!DOCTYPE html>
<html Lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=devide-width, initial-scale=1.0">
    <title>Mywallet - Histórico</title>
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

        .conteudo { padding: 28px} 

        .card {
            background: white;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 1px 6px rgba(0,0,0.09);
        }

        .card-cabecalho h3 { font-size: 1.05rem; color: #222; }

        .acoes-cabecalho { display: flex; gap: 8px; align-items: center; }

        .btn-voltar {
            text-decoration: none;
            color: #444;
            border: 1px solid #ccc;
            padding: 6px 14px;
            border-radius:6px;
            font-size: 0.83rem;
            background: white;
        }
        .btn-voltar:hover { background: #f5f5f5; }

        .btn-zerar {
            background: #dc3545;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.83rem;
            font-weight: bold;
        }

        .btn-zerar:hover { background: #b02a37; }

        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }

        thead th {
            text-align: left;
            padding: 10px 10px;
            color: #555;
            font-weight: bold;
            border-bottom: 2px solid #eee;
        }

        tbody td {
            padding: 11px 10px;
            border-bottom: 1px solid #f2f2f2;
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #fafafa; }

        
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.78rem;
            font-weight: bold;
        }

        .badge-receita { background: #d4edda; color: #155724; }
        .badge-despesa { background: #f8d7da; color: #721c24; }

        
        .valor-positivo { color: #198754; font-weight: bold; }
        .valor-negativo { color: #dc3545; font-weight: bold; }

        
        .pct-wrap { min-width: 80px; }

        .pct-label { font-size: 0.78rem; color: #888; margin-bottom: 3px; }

        .pct-barra {
            height: 6px;
            background: #f8d7da;
            border-radius: 4px;
        }

        .pct-fill {
            height: 100%;
            background: #dc3545;
            border-radius: 4px;
        }

        
        .btn-remover {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            font-size: 1rem;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .btn-remover:hover { background: #f8d7da; }

        .vazio {
            text-align: center;
            color: #aaa;
            padding: 40px 0;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

<nav>
    <span class="marca"> MyWallet</span>
    <div style="display:flex; align-items:center;">
        <span class="usuario">Olá, <?= htmlspecialchars($_SESSION['usuario']) ?> </span>
        <a href="logout.php" class="btn-sair">Sair</a>
    </div>
</nav>

<div class="conteudo">
    <div class="card">

        <div class="card-cabecalho">
            <h3>Histórico de Movimentações</h3>
            <div class="acoes-cabecalho">
                <a href="index.php" class="btn-voltar">← Voltar</a>
                <form method="POST" onsubmit="return confirm('Deseja zerar todo o histórico?')">
                    <input type="hidden" name="acao" value="zerar">
                    <button type="submit" class="btn-zerar">🗑 Zerar</button>
                </form>
            </div>
        </div>

        <?php if (empty($transacoes)): ?>
            <p class="vazio">Nenhuma transação registrada ainda.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th style="text-align:right">Valor</th>
                        <th>% Despesa</th>
                        <th style="text-align:center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transacoes as $i => $t): ?>
                        <?php
                            $isReceita = $t['tipo'] === 'receita';
                            $sinal     = $isReceita ? '+' : '-';
                            $classe    = $isReceita ? 'valor-positivo' : 'valor-negativo';
                            $pct       = $isReceita ? 0 : calcularPorcentagemDespesa($t['valor'], $totalDespesas);
                        ?>
                        <tr>
                            <td style="color:#999; font-size:0.8rem"><?= $t['data'] ?></td>
                            <td><strong><?= $t['nome'] ?></strong></td>
                            <td>
                                <span class="badge <?= $isReceita ? 'badge-receita' : 'badge-despesa' ?>">
                                    <?= $isReceita ? 'Receita' : 'Despesa' ?>
                                </span>
                            </td>
                            <td class="<?= $classe ?>" style="text-align:right">
                                <?= $sinal ?> <?= formatarMoeda($t['valor']) ?>
                            </td>
                            <td>
                                <?php if (!$isReceita): ?>
                                    <div class="pct-wrap">
                                        <div class="pct-label"><?= $pct ?>%</div>
                                        <div class="pct-barra">
                                            <div class="pct-fill" style="width:<?= min($pct, 100) ?>%"></div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span style="color:#ccc">–</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:center">
                                <form method="POST" onsubmit="return confirm('Remover esta transação?')">
                                    <input type="hidden" name="acao" value="remover">
                                    <input type="hidden" name="indice" value="<?= $i ?>">
                                    <button type="submit" class="btn-remover">✕</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>
</div>

</body>
</html>

