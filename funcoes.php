<?php
function calcularReceitas($transacoes) {
    $total = 0;
    foreach ($transacoes as $t) {
        if ($t['tipo'] === 'receita') {
            $total += $t['valor'];
        }
    }
    return $total;
}

function calcularDespesas($transacoes) {
    $total = 0;
    foreach ($transacoes as $t) {
        if ($t['tipo'] === 'despesa') {
            $total += $t['valor'];
        }
    }
    return $total;
}

function calcularSaldo($transacoes) {
    return calcularReceitas($transacoes) - calcularDespesas($transacoes);
}

function formatarMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function calcularPorcentagemDespesa($valor, $totalDespesas) {
    if ($totalDespesas == 0) return 0;
    return round(($valor / $totalDespesas) * 100, 2);
}
?>
