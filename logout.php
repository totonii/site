<?php
session_start(); // inicia a sessao para acessar os dados existentes
session_destroy(); // encerra a sessao atual e apaga todos os dados da sessao
header("Location: login.php"); // redireciona o usuario para a pagina de login
exit(); // encerra a execucao do script para garantir que nenhum codigo adicional seja executado
?>
