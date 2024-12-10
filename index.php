<?php
#inicia a sessão PHP
session_start();

#inclui o arquivo de configuração do banco de dados
include 'config.php';

#estabelece uma conexão com o banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);

#verifica se a conexão funcionou
if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error()); #exibe um erro e interrompe o script se a conexão falhar
}

#inicializa a variável para guardar a foto de perfil do usuário
$foto_perfil = null; 

#verifica se o usuário tá logado e tem um id na sessap
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['id'])) {
    $usuario_id = $_SESSION['id']; #pega o ID do usuário logado

    #prepara uma consulta SQL pra buscar foto de perfil do usuário
    $sql = "SELECT foto_perfil FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    #verifica se a consulta foi preparada corretamente
    if ($stmt) {
        #vincula o parâmetro ID à consulta
        mysqli_stmt_bind_param($stmt, "i", $usuario_id);
        #faz a consulta
        mysqli_stmt_execute($stmt);
        #obtém o resultado da consulta
        $result = mysqli_stmt_get_result($stmt);

        #verifica se um resultado foi encontrado
        if ($row = mysqli_fetch_assoc($result)) {
            $foto_perfil = $row['foto_perfil']; #armazena a foto de perfil
        } else {
            echo "Nenhum resultado encontrado para o usuário com ID: $usuario_id"; #mensagem caso não haja resultados
        }
        mysqli_stmt_close($stmt); #fecha a declaração preparada
    } else {
        echo "Erro ao preparar a consulta: " . mysqli_error($conn); #mostra um erro caso a preparação falhe
    }
} else {
    echo "Usuário não está logado ou ID não encontrado."; #mensagem caso o usuário não esteja logado
}

#fecha a conexão com o banco de dados
mysqli_close($conn); 
?>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $siteName; ?></title>
    <style>

        #darkModeToggle {
            background: none; 
            border: none;     
            padding: 0;       
            cursor: pointer;  
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #000;
            transition: background-color 0.3s, color 0.3s;
        }

        body.dark-mode {
            background-color: #121212;
            color: #fff;
        }

        body.dark-mode .header,
        body.dark-mode .navbar,
        body.dark-mode .footer {
            background-color: #222;
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
            border-radius: 50%;
            margin-right: 10px;
        }

        .header {
            background-color: #4a69bd;
            color: white;
            padding: 20px;
            text-align: center;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .header h1 {
            cursor: pointer;
        }

        .logout {
            display: block;
            position: absolute;
            top: 60px;
            left: 20px;
            background: none;
            border: none;
            font-size: 16px;
            color: white;
            cursor: pointer;
            text-decoration: underline;
        }

        .navbar {
            overflow: hidden;
            background-color: #4a69bd;
            margin-top: 42px;
            transition: background-color 0.3s;
        }

        .navbar a {
            float: right;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        body.dark-mode .navbar a:hover {
            background-color: #444;
            color: white;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
            margin-top: 120px;
        }

        .card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 10px;
            padding: 20px;
            width: 300px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: width 0.3s, height 0.3s;
            overflow: hidden;
            cursor: pointer;
        }

        #nomeUsuario img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .card:focus {
            outline: 2px solid #007bff;
        }

        .card.expanded {
            width: 90%;
            height: auto;
        }

        .card img {
            max-width: 100%;
            border-radius: 8px 8px 0 0;
        }

        .card h3 {
            margin-top: 10px;
        }

        .bmi-calculator {
            background-color: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 10px;
        }

        .bmi-calculator input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .bmi-calculator button {
            width: 100%;
            padding: 10px;
            background-color: #4a69bd;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .bmi-calculator button:hover {
            background-color: #3b5b96;
        }

        .dark-mode .card {
            background-color: #1e1e1e;
            color: #fff;
        }

        .dark-mode .bmi-calculator {
            background-color: #333;
        }

        .footer {
            background-color: #4a69bd;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 0;
        }

        .footer a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
        </style>
</head>

<body>
<header class="header"> <!-- início do cabeçalho -->
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?> <!-- verifica se o usuário está logado -->
        <div id="nomeUsuario"> <!-- div para exibir o nome e foto do usuário -->
            <a href="perfil.php"> <!-- manda para o perfil do usuário -->
                <?php if ($foto_perfil): ?> <!-- verifica se há uma foto de perfil disponível -->
                    <img src="uploads/<?php echo $foto_perfil; ?>" alt="Foto de Perfil" class="profile-picture"> <!-- Exibe a foto de perfil -->
                <?php else: ?>
                    <span>Imagem de Perfil</span> <!-- texto alternativo caso não tenha foto de perfil -->
                <?php endif; ?>
            </a>
            <span>Olá, <?php echo $_SESSION['nome']; ?>!</span> <!-- exibe saudação com o nome do usuário -->
        </div>
        <a class="logout" href="logout.php">Logout</a> <!-- manda para logout -->
    <?php else: ?> <!-- Caso o usuário não esteja logado -->
        <h1 onclick="redirectToHome()"><?php echo $siteName; ?></h1> <!-- exibe o nome do site como título -->
    <?php endif; ?>
    <button id="darkModeToggle">🌙</button> <!-- Botão para alternar entre modo claro e escuro -->
</header>
<button id="secretButton" style="display:none;" onclick="window.location.href='teste.php'">Segredo</button> <!-- Botão secreto escondido -->
<nav class="navbar"> <!-- barra de navegação -->
    <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?> <!-- verifica se o usuário não está logado -->
        <a href="login.php">Login</a> <!-- manda para a página de login -->
        <a href="registro.php">Registro</a> <!-- manda para a página de registro -->
    <?php endif; ?>
    <a href="noticias.php">Notícias</a> <!-- manda para a página de notícias -->
    <a href="contato.php">Contato</a> <!-- manda para a página de contato -->
</nav>



    <main> <!-- seção principal do conteúdo -->
        <section class="container"> <!-- seção com a classe container -->
            <div class="card" onclick="app.toggleCard(this)"> <!-- cartão com evento de alternar o estado do cartão -->
                <img src="boxing.jpg" alt="Boxe"> <!-- imagem do box com texto alternativo -->
                <h3>BOXE</h3> <!-- título da seção de boxe -->
                <p>Descubra a força interior e a técnica impecável necessárias para se destacar no ringue. Desafie-se a superar seus limites físicos e mentais enquanto aprende os segredos deste esporte de combate emocionante.</p> <!-- Descrição do conteúdo sobre boxe -->
            </div><!-- fim do cartão -->

            <div class="card" onclick="app.toggleCard(this)"> 
                <img src="crossfit.jpg" alt="Crossfit">
                <h3>CROSSFIT</h3>
                <p>Entre na arena do crossfit e desafie seu corpo em um treinamento intenso e variado que irá transformar sua força, resistência e condicionamento físico. Supere seus limites e alcance novos patamares de desempenho.</p>
            </div>

            <div class="card" onclick="app.toggleCard(this)"> 
                <img src="snow_sports.jpg" alt="Esportes na Neve">
                <h3>ESPORTES NA NEVE</h3>
                <p>Sinta a adrenalina das montanhas cobertas de neve enquanto desliza pelas encostas em esportes como esqui e snowboard. Prepare-se para a emoção de voar sobre a neve e dominar as pistas.</p>
            </div>            <div class="card" onclick="app.toggleCard(this)"> 
                <img src="basketball.jpg" alt="Basquete">
                <h3>BASQUETE</h3>
                <p>Drible, passe, arremesse! Junte-se ao emocionante mundo do basquete e experimente a empolgação de jogar em equipe, competir em partidas acirradas e fazer cestas incríveis.</p>
            </div>

            <div class="card" onclick="app.toggleCard(this)"> 
                <img src="running.jpg" alt="Corrida">
                <h3>CORRIDA</h3>
                <p>Calce seus tênis e sinta a energia pulsante das corridas, desafiando-se a correr mais longe e mais rápido. Experimente a sensação de liberdade e conquista a cada passo.</p>
            </div>

            <div class="card" onclick="app.toggleCard(this)"> 
                <img src="surfing.jpg" alt="Surf">
                <h3>SURF</h3>
                <p>Sinta a liberdade e a conexão com o mar enquanto desliza sobre as ondas. Domine a arte do surf e experimente a emoção de surfar em diferentes tipos de ondas e praias.</p>
            </div>

            <div class="card" onclick="app.toggleCard(this)"> 
                <img src="hiking.jpg" alt="Trilha">
                <h3>TRILHA</h3>
                <p>Aventure-se pelos caminhos menos percorridos e descubra a beleza da natureza em suas trilhas. Desafie-se a superar terrenos variados e desfrute da serenidade das paisagens naturais.</p>
            </div>

            <div class="card" onclick="app.toggleCard(this)"> 
                <img src="cycling.jpg" alt="Ciclismo">
                <h3>CICLISMO</h3>
                <p>Suba na bicicleta e sinta a emoção de pedalar por diferentes terrenos e paisagens. Desafie-se em provas de velocidade, resistência e habilidade sobre duas rodas.</p>
            </div>
        </section><!-- fim da seção com a classe container -->

        <section class="container"> <!-- início de uma seção com a classe 'container', usada para organizar o layout -->
    <div class="bmi-calculator"> <!-- div que encapsula a calculadora de IMC -->
        <h2>Calculadora de IMC</h2> <!-- cabeçalho com o título da calculadora de IMC -->
        <label for="weight">Peso (KG)</label> <!-- rotulo para o campo de entrada de peso associado ao id 'weight' -->
        <input type="number" id="weight" placeholder="Digite o peso..."> <!-- campo de entrada para o peso, do tipo numérico, com um texto de exemplo -->
        <label for="height">Altura (M)</label> <!-- rotulo para o campo de entrada de altura associado ao id 'height' -->
        <input type="number" id="height" placeholder="Digite a altura..."> <!-- campo de entrada para a altura, do tipo numérico, com um texto de exemplo -->
        <button id="calculateBMIButton">Calcular IMC</button> <!-- Botão para acionar o cálculo do IMC -->
    </div> <!-- fim da div da calculadora de IMC -->
</section> <!-- fim da seção com a classe 'container' -->

    </main> <!-- fim da seção principal do conteúdo -->

    <footer class="footer"> <!-- início do rodapé com a classe 'footer' -->
    <a href="login.php">Login</a> <!-- lmanda para a página de login -->
    <a href="registro.php">Registro</a> <!-- manda para a página de registro -->
    <a href="contato.php">Contato</a> <!-- manda para a página de contato -->
    </footer> <!-- fim do rodapé -->

<script src="darkmode.js"></script> // Importa o script responsável pelo modo escuro

<script>
    const app = {
        // função para alternar o estado de expansão de um cartão
        toggleCard: function(card) {
            card.classList.toggle('expanded'); // adiciona ou remove a classe 'expanded' do cartão
        },

        // função para calcular o IMC
        calculateBMI: function() {
            const weightInput = document.getElementById('weight'); // obtém o campo de peso
            const heightInput = document.getElementById('height'); // obtém o campo de altura

            const weight = parseFloat(weightInput.value); // converte o valor do peso para número
            const height = parseFloat(heightInput.value); // converte o valor da altura para número

            // verifica se os valores são válidos e positivos
            if (isNaN(weight) || isNaN(height) || weight <= 0 || height <= 0) {
                alert('Por favor, insira valores numéricos válidos e positivos para peso e altura.');
                return; // Sai da função se os valores forem inválidos
            }

            const bmi = (weight / (height * height)).toFixed(2); // Calcula o IMC com duas casas decimais
            alert('Seu IMC é: ' + bmi); // Exibe o IMC em um alerta
        },

        // Função para redirecionar para uma URL
        redirectTo: function(url) {
            window.location.href = url; // redireciona o usuário para a URL especificada
        },

        // função para rolar a página para o topo
        scrollToTop: function() {
            window.scrollTo({
                top: 0, // define o topo da página como destino
                behavior: 'smooth' // usa animação suave para a rolagem
            });
        }
    };

    // adiciona um evento de clique ao botão de calcular o IMC
    document.getElementById('calculateBMIButton').addEventListener('click', app.calculateBMI);

    // seleciona todos os links no navbar e no footer
    const navLinks = document.querySelectorAll('.navbar a, .footer a');
    navLinks.forEach(link => {
        link.addEventListener('click', (event) => { // adiciona evento de clique aos links
            event.preventDefault(); // evita o comportamento padrão do clique
            app.redirectTo(link.href); // redireciona para o link usando a função do app
        });
    });

    // adiciona evento de clique ao cabeçalho para rolar para o topo
    document.querySelector('.header').addEventListener('click', app.scrollToTop);

    // adiciona evento de clique ao botão de alternar o modo escuro
    document.getElementById('darkModeToggle').addEventListener('click', DarkMode.toggle);
</script>

<script> // script de fazer um botão secreto aparecer que me leva pra teste.php
    // verifica se o usuário está logado
    let isLoggedIn = <?php echo isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ? 'true' : 'false'; ?>;
    let clickCount = 0; // Inicializa o contador de cliques no body

    // adiciona evento de clique ao body
    document.body.addEventListener('click', function(event) {
        if (event.target === document.body) { // verifica se o clique foi no body
            clickCount++; // incrementa o contador de cliques
            console.log('Clique detectado no body:', clickCount); // Log do clique no console

            // exibe o botão secreto se as condições forem atendidas
            if (darkModeEnabled() && isLoggedIn && clickCount >= 5) {
                console.log('Exibindo o botão secreto'); // log da exibição do botão
                document.getElementById('secretButton').style.display = 'block'; // mostra um botão secreto
            }
        }
    });

    // função para verificar se o modo escuro está ativado
    function darkModeEnabled() {
        return document.body.classList.contains('dark-mode'); // retorna true se a classe 'dark-mode' estiver no body
    }
</script>

<button id="secretButton" style="display:none;" onclick="window.location.href='teste.php'">Segredo</button> 

</body> <!-- fim do corpo da página -->
</html> <!-- fim do documento HTML -->


            
