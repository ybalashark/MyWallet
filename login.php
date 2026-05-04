
<?php
include("autent.php");

if(isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$erro = " ";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    if(login($usuario, $senha)) {
        header("Location: index.php");
        exit();
    }

    else {
        $erro = "Usuário ou senha inválidos";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyWallet – Login</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .card-login {
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .card-topo {
            background: #1a1a2e;
            color: white;
            text-align: center;
            padding: 32px 24px;
        }

        .card-topo .icone { font-size: 2.5rem; margin-bottom: 8px; }
        .card-topo h2 { font-size: 1.6rem; margin-bottom: 4px; }
        .card-topo p { font-size: 0.85rem; color: #aaa; }

        .card-corpo {
            background: white;
            padding: 32px 28px;
        }

        .erro {
            background: #f8d7da;
            color: #721c24;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 0.85rem;
            margin-bottom: 16px;
        }

        .campo { margin-bottom: 18px; }

        .campo label {
            display: block;
            font-size: 0.72rem;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 6px;
        }

        .input-grupo {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 6px;
            overflow: hidden;
        }

        .input-grupo span {
            padding: 10px 12px;
            background: #f5f5f5;
            font-size: 1rem;
            border-right: 1px solid #ccc;
        }

        .input-grupo input {
            border: none;
            outline: none;
            padding: 10px 12px;
            width: 100%;
            font-size: 0.95rem;
        }

        .btn-entrar {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: bold;
            letter-spacing: 1px;
            cursor: pointer;
            margin-top: 8px;
        }

        .btn-entrar:hover { opacity: 0.9; }

        .rodape {
            text-align: center;
            font-size: 0.78rem;
            color: #aaa;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="card-login">
    <div class="card-topo">
        <div class="icone">(0_0)/</div>
        <h2>MyWallet</h2>
        <p>Gestão Financeira Pessoal</p>
    </div>

    <div class="card-corpo">
        <?php if ($erro): ?>
            <div class="erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="campo">
                <label>Utilizador</label>
                <div class="input-grupo">
                    <span>0/</span>
                    <input type="text" name="usuario" placeholder="admin" required>
                </div>
            </div>

            <div class="campo">
                <label>Palavra-Passe</label>
                <div class="input-grupo">
                    <span>#</span>
                    <input type="password" name="senha" placeholder="••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-entrar">ENTRAR NO SISTEMA</button>
        </form>

        <p class="rodape">Academia PHP só confia que dá bom © 2026</p>
    </div>
</div>

</body>
</html>
