<?php
session_start(); // inicia a sessao do usuario
include 'config.php'; // inclui as configuracoes do banco de dados

// pega os dados do formulario
$email = $_POST['email']; // pega o email enviado pelo formulario
$senha = $_POST['senha']; // pega a senha enviada pelo formulario

// conecta no banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname); 
if (!$conn) { // se nao conectar, mostra o erro e para tudo
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

// faz uma consulta no banco pra pegar os dados do usuario pelo email
$sql = "SELECT id, nome, senha FROM usuarios WHERE email = ?"; // prepara a query
$stmt = mysqli_prepare($conn, $sql); // prepara o statement pra evitar sql injection
mysqli_stmt_bind_param($stmt, "s", $email); // liga o parametro email na query
mysqli_stmt_execute($stmt); // executa a query
$result = mysqli_stmt_get_result($stmt); // pega o resultado da consulta

if (mysqli_num_rows($result) == 1) { // se encontrou um usuario
    $row = mysqli_fetch_assoc($result); // pega os dados desse usuario
    if (password_verify($senha, $row['senha'])) { // verifica se a senha enviada bate com a do banco
        $_SESSION['loggedin'] = true; // marca que o usuario ta logado
        $_SESSION['id'] = $row['id']; // armazena o id do usuario na sessao
        $_SESSION['nome'] = $row['nome']; // armazena o nome do usuario na sessao

        header("Location: index.php"); // redireciona pra pagina principal
    } else {
        echo "Senha incorreta."; // avisa que a senha ta errada
    }
} else {
    echo "Usuário não encontrado."; // avisa que nao achou nenhum usuario com esse email
}

// fecha as conexoes com o banco
mysqli_stmt_close($stmt); 
mysqli_close($conn); 
?>
