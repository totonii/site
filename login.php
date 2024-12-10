<!DOCTYPE html>
<?php
session_start(); // Adicione esta linha
include 'config.php';

// Conectar ao banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

// Obter a foto de perfil do usuário 
$foto_perfil = null; // Inicializa a variável
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $usuario_id = $_SESSION['id'];
    $sql = "SELECT foto_perfil FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $usuario_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $foto_perfil = $row['foto_perfil'];
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo $siteName; ?></title>
    <style>
        /* Reseta os estilos básicos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; 
            color: #333;          
            line-height: 1.6;
            margin: 0;
            transition: background-color 0.3s, color 0.3s; 
        }

        /* Estilos para o modo escuro */
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

        header button {
            background: none;
            border: none;
            font-size: 24px;
            color: white;
            cursor: pointer;
        }

        .container {
            padding: 20px;
            max-width: 600px;
            margin: 100px auto; 
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

        header a img.profile-picture {
            width: 40px; 
            height: 40px; 
            border-radius: 50%;
            object-fit: cover; 
            max-width: 100%; /* Garante que a imagem não ultrapasse a largura do container */
            max-height: 100%; /* Garante que a imagem não ultrapasse a altura do container */
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
        </style>
</head>
<body>
<header class="header">
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <div id="nomeUsuario">
            <a href="perfil.php">
                <?php if ($foto_perfil): ?>
                    <img src="uploads/<?php echo $foto_perfil; ?>" alt="Foto de Perfil" class="profile-picture">
                <?php else: ?>
                    <span>Imagem de Perfil</span>
                <?php endif; ?>
            </a> 
            <span>Olá, <?php echo $_SESSION['nome']; ?>!</span>
        </div>
    <?php else: ?>
        <h1 onclick="redirectToHome()"><?php echo $siteName; ?></h1>
    <?php endif; ?>
    <button id="darkModeToggle">🌙</button>
</header>


    <div class="container">
        <h2>Login</h2>
        <form action="processar_login.php" method="post"> 
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password"   
 placeholder="Senha" required>
            <button type="submit">Entrar</button>   

        </form>
    </div>

    <footer>
        <p onclick="redirectToHome()"><?php echo $siteName; ?></p>
    </footer>

    <script src="darkmode.js"></script> 
    <script>
        // Função para redirecionar para a página principal
        function redirectToHome() {
            window.location.href = 'index.php';
        }

        // Adicione o evento de clique ao botão de modo escuro
        document.getElementById('darkModeToggle').addEventListener('click', DarkMode.toggle);

        // Aplicar o tema ao carregar a página
        window.onload = DarkMode.apply; 
    </script>
</body>
</html>
