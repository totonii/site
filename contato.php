<!DOCTYPE html>
<?php include 'config.php'; ?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - <?php echo $siteName; ?></title>
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f8f8;
    color: #333;
    line-height: 1.6;
}

header {
    background-color: #4a69bd; 
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 1000;
}

header h1 {
    cursor: pointer;
    font-size: 24px;
}

header button {
    background: none;
    border: none;
    font-size: 24px;
    color: white;
    cursor: pointer;
}

.container {
    padding: 30px;
    max-width: 600px;
    margin: 100px auto;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

h2 {
    margin-bottom: 20px;
    font-weight: bold;
    color: #4a69bd;
}

input, textarea {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 4px; 
    font-size: 16px;
}

input:focus, textarea:focus {
    border-color: #4a69bd; 
    outline: none; 
}

button {
    width: 100%;
    padding: 10px;
    background-color: #4a69bd; 
    color: white;
    border: none;
    border-radius: 4px; 
    cursor: pointer;
    font-size: 18px;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #3b5b9a; 
}

footer {
    background-color: #4a69bd;
    color: white;
    text-align: center;
    padding: 10px 0;
}

footer p {
    margin: 0;
    cursor: pointer;
}

    </style>
</head>
<body>
<?php
use PHPMailer\PHPMailer\PHPMailer; // chama a classe do phpmailer pra mandar email
use PHPMailer\PHPMailer\Exception; // chama a classe de excecoes do phpmailer

require 'vendor/autoload.php'; // carrega as dependencias do composer

if ($_SERVER["REQUEST_METHOD"] == "POST") { // verifica se o formulario foi enviado via post
    $nome = htmlspecialchars($_POST['nome']); // pega o nome e remove tags html
    $sobrenome = htmlspecialchars($_POST['sobrenome']); // pega o sobrenome e remove tags html
    $email = htmlspecialchars($_POST['email']); // pega o email e remove tags html
    $telefone = htmlspecialchars($_POST['telefone']); // pega o telefone e remove tags html
    $mensagem = htmlspecialchars($_POST['mensagem']); // pega a mensagem e remove tags html

    $mail = new PHPMailer(true); // instancia o phpmailer e deixa ele preparado pra tratar erros

    try {
        $mail->isSMTP(); // diz pro phpmailer que vai usar smtp pra enviar o email
        $mail->Host = 'smtp.gmail.com'; // define o servidor smtp
        $mail->SMTPAuth = true; // ativa autenticacao smtp
        $mail->Username = 'testemaximo10@gmail.com'; // coloca o email do remetente
        $mail->Password = 'awoo qnld nxmu lzwl'; // senha do email
        $mail->SMTPSecure = 'tls'; // define a seguranca tls
        $mail->Port = 587; // porta usada pelo servidor smtp

        $mail->setFrom($email, "$nome $sobrenome"); // define o remetente com nome e email enviados pelo formulario
        $mail->addAddress('totonicontato@gmail.com'); // email do destinatario

        $mail->isHTML(true); // diz que o conteudo vai ser em html
        $mail->Subject = 'Mensagem de Contato do Site'; // define o assunto do email
        $mail->Body = nl2br("Nome: $nome\nSobrenome: $sobrenome\nEmail: $email\nTelefone: $telefone\n\nMensagem:\n$mensagem"); 
        // monta o corpo do email com os dados do formulario

        $mail->send(); // tenta enviar o email
        echo "<script>alert('Mensagem enviada com sucesso!');</script>"; // avisa que deu certo
    } catch (Exception $e) {
        echo "<script>alert('Erro ao enviar mensagem: {$mail->ErrorInfo}');</script>"; // avisa que deu erro e mostra a mensagem do erro
    }
}
?>

<header>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): // checa se o usuario ta logado ?>
        <a href="perfil.php"> 
            <?php if (!empty($_SESSION['foto_perfil'])): // checa se o usuario tem foto de perfil ?>
                <img src="uploads/<?php echo htmlspecialchars($_SESSION['foto_perfil']); ?>" alt="Foto de Perfil" class="profile-picture"> 
                <!-- mostra a foto de perfil -->
            <?php else: ?>
                <span>Perfil</span> <!-- mostra "perfil" se nao tiver foto -->
            <?php endif; ?>
        </a>
    <?php else: ?>
        <h1 onclick="redirectToHome()"><?php echo $siteName; ?></h1> <!-- mostra o nome do site se nao tiver logado -->
    <?php endif; ?>

    <button id="darkModeToggle">🌙</button> <!-- botao pra trocar o modo escuro/claro -->

    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): // mostra o logout se o usuario ta logado ?>
        <a href="logout.php">Logout</a>
    <?php endif; ?>
</header>

<div class="container">
    <h2>Contate-nos</h2> <!-- titulo do formulario de contato -->
    <form action="" method="post"> <!-- formulario pra enviar os dados -->
        <input type="text" name="nome" placeholder="Nome" required> <!-- campo pro nome -->
        <input type="text" name="sobrenome" placeholder="Sobrenome" required> <!-- campo pro sobrenome -->
        <input type="email" name="email" placeholder="Email" required> <!-- campo pro email -->
        <input type="text" name="telefone" placeholder="Telefone"> <!-- campo opcional pro telefone -->
        <textarea name="mensagem" placeholder="Digite aqui sua mensagem" required></textarea> <!-- campo pra mensagem -->
        <button type="submit">Enviar</button> <!-- botao de enviar -->
    </form>
</div>

<footer>
    <p onclick="redirectToHome()"><?php echo $siteName; ?></p> <!-- nome do site no rodape que redireciona pro inicio -->
</footer>

<script>
    function redirectToHome() { 
        window.location.href = 'index.php'; // redireciona pra pagina inicial
    }
</script>
</body>
</html>
