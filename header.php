

<?php
session_start(); 
include 'config.php'; 



// Verifica se o usuário está logado e obtém a foto de perfil, se existir
$foto_perfil = null;
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Conectar ao banco de dados (reutilize a conexão se já estiver aberta)
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
    }

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
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . $siteName : $siteName; ?></title> 
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <header>
        <div class="profile-container"> 
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <a href="perfil.php"> 
                    <?php if ($foto_perfil): ?>
                        <img src="uploads/<?php echo $foto_perfil; ?>" alt="Foto de Perfil" class="profile-picture">
                    <?php else: ?>
                        <span>Perfil</span> 
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        </div>

        <h1><?php echo $siteName; ?></h1> 
        <button id="darkModeToggle">🌙</button>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </header>

    <nav class="navbar">
        <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?>
            <a href="login.php">Login</a>
            <a href="registro.php">Registro</a>
        <?php endif; ?>
        <a href="contato.php">Contato</a> 
    </nav>