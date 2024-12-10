<?php
session_start(); // inicia uma sessao para armazenar dados temporarios do usuario
include 'config.php'; // inclui o arquivo de configuracao contendo variaveis como $servername e $username
$conn = mysqli_connect($servername, $username, $password, $dbname); // cria uma conexao com o banco de dados usando os parametros do arquivo de configuracao

if (!$conn) { // verifica se a conexao com o banco de dados falhou
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error()); // encerra o script e exibe uma mensagem de erro se a conexao falhar
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) { // verifica se o usuario nao esta logado
    header("Location: login.php"); // redireciona o usuario para a pagina de login
    exit(); // encerra a execucao do script
}

$conn = mysqli_connect($servername, $username, $password, $dbname); // reconecta ao banco de dados para garantir que a conexao esta ativa
if (!$conn) { // verifica novamente se a conexao falhou
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error()); // encerra o script e exibe uma mensagem de erro se a conexao falhar
}

$usuario_id = $_SESSION['id']; // recupera o id do usuario logado da sessao
$sql = "SELECT nome, email, foto_perfil, admin FROM usuarios WHERE id = ?"; // prepara uma query sql para buscar dados do usuario
$stmt = mysqli_prepare($conn, $sql); // prepara a query para execucao no banco de dados
mysqli_stmt_bind_param($stmt, "i", $usuario_id); // associa o valor de $usuario_id ao parametro na query sql
mysqli_stmt_execute($stmt); // executa a query preparada
$result = mysqli_stmt_get_result($stmt); // recupera o resultado da execucao da query

if (mysqli_num_rows($result) == 1) { // verifica se exatamente um registro foi encontrado
    $row = mysqli_fetch_assoc($result); // extrai os dados do registro encontrado como um array associativo
    $nome = $row['nome']; // armazena o nome do usuario na variavel $nome
    $email = $row['email']; // armazena o email do usuario na variavel $email
    $foto_perfil = $row['foto_perfil']; // armazena o caminho da foto de perfil na variavel $foto_perfil
    $isAdmin = $row['admin'] === 'sim'; // verifica se o usuario e administrador e armazena o resultado na variavel $isAdmin
} else { // caso nao encontre exatamente um registro
    echo "Erro ao obter dados do usuário."; // exibe uma mensagem de erro
    exit(); // encerra a execucao do script
}

mysqli_stmt_close($stmt); // fecha o statement preparado
mysqli_close($conn); // fecha a conexao com o banco de dados
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - <?php echo $siteName; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #3b5998;
            color: white;
            padding: 10px;
            text-align: center;
            position: relative;
        }

        header img.profile-picture {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            position: absolute;
            top: 10px;
            left: 10px;
        }

        header button#darkModeToggle {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        .container {
            background-color: white;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .container .profile-picture-container {
            display: block;
            margin: 0 auto 20px;
            width: 200px; 
            height: 200px; 
            border-radius: 50%;
            background-size: cover; 
            background-position: center; 
        }
        .container img.profile-picture {
            display: block;
            margin: 0 auto 20px;
            width: 200px; 
            height: 200px; 
            border-radius: 50%;
            object-fit: cover; 
        }

        p {
            font-size: 18px;
            color: #555;
            text-align: center;
        }

        form {
            text-align: center;
        }

        form button {
            background-color: #3b5998;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        form button:hover {
            background-color: #324b80;
        }

        .admin-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #3b5998;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .admin-button:hover {
            background-color: #324b80;
        }

        footer {
            background-color: #3b5998;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <?php if ($foto_perfil): ?> 
            <!-- verifica se o usuario possui uma foto de perfil -->
            <img src="uploads/<?php echo $foto_perfil; ?>" alt="Foto de Perfil" class="profile-picture">
            <!-- exibe a foto de perfil do usuario se existir -->
        <?php endif; ?>
        <button id="darkModeToggle">🌙</button>
        <!-- botao para alternar o modo escuro -->
    </header>

    <div class="container">
        <h2>Meu Perfil</h2>
        <!-- titulo da pagina de perfil -->

        <?php if ($foto_perfil): ?>
            <!-- verifica novamente se o usuario possui uma foto de perfil -->
            <img src="uploads/<?php echo $foto_perfil; ?>" alt="Foto de Perfil" class="profile-picture">
            <!-- exibe a foto de perfil se existir -->
        <?php else: ?>
            <p>Você ainda não tem uma foto de perfil.</p>
            <!-- mensagem exibida se o usuario nao tiver uma foto de perfil -->
        <?php endif; ?>

        <p><strong>Nome:</strong> <?php echo $nome; ?></p>
        <!-- exibe o nome do usuario -->
        <p><strong>Email:</strong> <?php echo $email; ?></p>
        <!-- exibe o email do usuario -->

        <form action="alterar_foto.php" method="post" enctype="multipart/form-data">
            <!-- formulario para alterar a foto de perfil -->
            <input type="file" name="foto_perfil">
            <!-- campo para selecionar o arquivo da nova foto de perfil -->
            <button type="submit">Alterar Foto de Perfil</button>
            <!-- botao para enviar o formulario -->
        </form>

        <?php if ($isAdmin): ?>
            <!-- verifica se o usuario e administrador -->
            <a href="criarnoticia.php" class="admin-button">Criar Notícia</a>
            <!-- manda para a pagina de criacao de noticias se o usuario for administrador -->
        <?php endif; ?>
    </div>

    <footer>
        <h1 onclick="redirectToHome()"><?php echo $siteName; ?></h1>
        <!-- exibe o nome do site no rodape e redireciona para a home ao clicar -->
    </footer>

    <script src="darkmode.js"></script>
    <!-- script para funcionalidade de modo escuro -->
    <script>
        function redirectToHome() {
            window.location.href = "index.php"; //manda pra pagina principal
        }
    </script>
</body>
</html>

