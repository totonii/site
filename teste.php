<?php
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'testedebancodedados');
// abre uma conexão com o banco usando os dados passados

// Pega os dados da notícia
$sql = 'SELECT titulo, descricao, imagem FROM noticias WHERE id = 1';
// cria uma query pra pegar o título, descrição e imagem de uma notícia com id = 1
$result = $conn->query($sql);
// executa a query e guarda o resultado

if ($result->num_rows > 0) {
    // verifica se veio algum resultado da query
    $row = $result->fetch_assoc();
    // pega a primeira linha do resultado como um array associativo
    $titulo = $row['titulo'];
    // guarda o título da notícia
    $descricao = $row['descricao'];
    // guarda a descrição da notícia
    $imagem = $row['imagem'];
    // guarda o caminho da imagem da notícia
} else {
    echo 'Notícia não encontrada.';
    // exibe mensagem caso não encontre a notícia
    exit;
    // para o script, já que não tem notícia pra mostrar
}
?>
<!DOCTYPE html>
<html lang='pt-BR'>
<!-- documento HTML com linguagem definida como português do Brasil -->
<head>
    <meta charset='UTF-8'>
    <!-- define o tipo de caractere como UTF-8 -->
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <!-- faz a página se ajustar ao tamanho da tela -->
    <title><?php echo $titulo; ?></title>
    <!-- usa o título da notícia como título da página -->
    <style>
        /* estilos básicos pra página */
        body { font-family: Arial, sans-serif; }
        /* define a fonte do corpo da página */
        h1 { color: #333; }
        /* deixa o título com uma cor cinza escura */
        .content { margin: 20px; }
        /* coloca um espaço ao redor do conteúdo */
    </style>
</head>
<body>
    <div class='content'>
        <!-- área que contém o conteúdo da notícia -->
        <h1><?php echo $titulo; ?></h1>
        <!-- exibe o título da notícia -->
        <p><?php echo $descricao; ?></p>
        <!-- exibe a descrição da notícia -->
        <img src='<?php echo $imagem; ?>' alt='Imagem da Notícia' style='max-width: 100%; height: auto;'>
        <!-- exibe a imagem da notícia com estilo responsivo -->
    </div>
</body>
</html>
