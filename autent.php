<?php
session_start();

$usuario_correto = "admin";
$senha_hash = password_hash("123456", PASSWORD_DEFAULT);

if (!isset($_SESSION['transacoes'])) {
    $_SESSION['transacoes'] = [];
}

function login($usuario, $senha) {
    global $usuario_correto, $senha_hash;

    if($usuario === $usuario_correto && password_verify($senha, $senha_hash)) {
        $_SESSION['usuario'] = $usuario;
        return true;
    }
    return false;
}

function verificarLogin() {
    if(!isset($_SESSION['usuario'])) {
        header("Location: login.php");
        exit();
    }
}



?>