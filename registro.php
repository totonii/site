<!DOCTYPE html>
<?php
session_start(); // inicia a sessao pro usuario logar e manter info ativa
include 'config.php'; // inclui as configs do banco

// conecta no banco
$conn = mysqli_connect($servername, $username, $password, $dbname); // faz a conexao
if (!$conn) { // verifica se deu ruim
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error()); // encerra se nao conectar
}

// pega a foto de perfil do usuario
$foto_perfil = null; // inicializa vazio pra nao dar erro se nao tiver nada
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { // checa se o usuario ta logado
    $usuario_id = $_SESSION['id']; // pega o id do usuario da sessao
    $sql = "SELECT foto_perfil FROM usuarios WHERE id = ?"; // query pra buscar a foto
    $stmt = mysqli_prepare($conn, $sql); // prepara a query pra evitar sql injection
    mysqli_stmt_bind_param($stmt, "i", $usuario_id); // coloca o id na query
    mysqli_stmt_execute($stmt); // executa a query
    $result = mysqli_stmt_get_result($stmt); // pega o resultado

    if (mysqli_num_rows($result) == 1) { // verifica se achou um registro
        $row = mysqli_fetch_assoc($result); // pega os dados
        $foto_perfil = $row['foto_perfil']; // salva a foto na variavel
    }

    mysqli_stmt_close($stmt); // fecha o statement
}

mysqli_close($conn); // fecha a conexao com o banco
?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - <?php echo $siteName; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        #nomeUsuario {
            z-index: 1;
            position: absolute;
            top: 15px;
            left: 20px;
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        #nomeUsuario img {
            width: 50px;
            height: 50px; 
            border-radius: 50%; 
            object-fit: cover; 
            margin-right: 10px;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
            margin: 0;
        }

        header {
            background-color: #4a69bd;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        header h1 {
            margin: 0;
            cursor: pointer;
        }

        #darkModeToggle {
            background: none;
            border: none;
            font-size: 24px;
            color: white;
            cursor: pointer;
        }

        .container {
            padding: 20px;
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f4f4f4;
            color: #333;
        }

        form button {
            width: 100%;
            padding: 10px;
            background-color: #4a69bd;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #3b5b96;
        }

        footer {
            background-color: #4a69bd;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        footer p {
            margin: 0;
            cursor: pointer;
        }

        .dark-mode {
            background-color: #333;
            color: white;
        }

        .dark-mode header, .dark-mode footer {
            background-color: #222;
        }

        .dark-mode .container {
            background-color: #444;
            color: white;
        }

        .dark-mode form input {
            background-color: #555;
            color: white;
            border-color: #777;
        }

        .dark-mode form button {
            background-color: #666;
        }

        .dark-mode form button:hover {
            background-color: #777;
        }
        </style>
</head>
<body>
<header class="header">
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <!-- verifica se o usuario ta logado -->
        <div id="nomeUsuario">
            <a href="perfil.php">
                <!-- cria um link pro perfil do usuario -->
                <?php if ($foto_perfil): ?>
                    <!-- checa se o usuario tem foto de perfil -->
                    <img src="uploads/<?php echo $foto_perfil; ?>" alt="Foto de Perfil" class="profile-picture">
                    <!-- se tiver, mostra a foto -->
                <?php else: ?>
                    <span>Imagem de Perfil</span>
                    <!-- se nao tiver, mostra um texto generico -->
                <?php endif; ?>
            </a> 
            <span>Olá, <?php echo $_SESSION['nome']; ?>!</span>
            <!-- da um oi pro usuario com o nome dele -->
        </div>
    <?php else: ?>
        <!-- se o usuario nao tiver logado -->
        <h1 onclick="redirectToHome()"><?php echo $siteName; ?></h1>
        <!-- mostra o nome do site como titulo, que redireciona pra home -->
    <?php endif; ?>
    <button id="darkModeToggle">🌙</button>
    <!-- botao pra trocar entre modo claro e escuro -->
</header>

<div class="container">
    <h2>Cadastre-se para acompanhar as notícias!</h2>
    <!-- titulo da area de cadastro -->
    <form action="processar_registro.php" method="post"> 
        <!-- formulario que manda os dados pro backend -->
        <input type="text" name="nome" placeholder="Nome" required>
        <!-- campo pro usuario digitar o nome -->
        <input type="email" name="email" placeholder="Email" required>
        <!-- campo pro email -->
        <input type="password" name="password" placeholder="Senha" required>
        <!-- campo pra senha -->
        <input type="tel" name="phone" placeholder="Telefone" required>   
        <!-- campo pro numero de telefone -->
        <button type="submit">Concluir</button>
        <!-- botao pra enviar o formulario -->
    </form>
</div>

<footer>
    <p onclick="redirectToHome()"><?php echo $siteName; ?></p>
    <!-- rodape com o nome do site que redireciona pra home -->
</footer>

<script src="darkmode.js"></script> 
<!-- script externo pra gerenciar o modo escuro -->
<script>
    // Funcao pra redirecionar pra home
    function redirectToHome() {
        window.location.href = 'index.php';
        // manda o usuario pra pagina principal
    }

    // Adiciona um evento de clique no botao de modo escuro
    document.getElementById('darkModeToggle').addEventListener('click', DarkMode.toggle);
    // ao clicar, troca o tema entre claro e escuro

    // Aplica o tema certo ao carregar a pagina
    window.onload = DarkMode.apply; 
    // garante que o tema ta correto quando a pagina abre
</script>
</body>
</html>


