<?php
session_start(); // inicia a sessao pra caso precise mais tarde

include 'config.php'; // puxa as configuracoes do banco de dados

// pega os dados que o usuario enviou no formulario
$nome = $_POST['nome']; // pega o nome do usuario
$email = $_POST['email']; // pega o email
$senha = $_POST['senha']; // pega a senha
$telefone = $_POST['telefone'];// pega o telefone
// valida os dados (aqui vc pode colocar verificacoes tipo email valido ou nome vazio)

// criptografa a senha antes de salvar no banco
$senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

// conecta no banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) { // verifica se deu erro na conexao
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

// prepara a query pra inserir os dados do usuario no banco
$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)"; 
$stmt = mysqli_prepare($conn, $sql); // evita sql injection
mysqli_stmt_bind_param($stmt, "sss", $nome, $email, $senha_criptografada); // liga os valores na query

if (mysqli_stmt_execute($stmt)) { // tenta executar a query
    header("Location: login.php"); // se deu certo, redireciona pro login
} else {
    echo "Erro ao registrar usuário: " . mysqli_error($conn); // se deu erro mostra o problema
}

// fecha as conexoes com o banco
mysqli_stmt_close($stmt);
mysqli_close($conn); 
?>
