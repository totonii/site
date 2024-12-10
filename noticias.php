<!DOCTYPE html>
<html lang="en">
<head><?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "testedebancodedados");

// Query para pegar todas as notícias
$sql = "SELECT id, titulo, descricao, imagem FROM noticias ORDER BY id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $noticia_id = $row['id'];
        $titulo = $row['titulo'];
        $descricao = $row['descricao'];
        $imagem = $row['imagem'];
        $pagina_noticia = strtolower(str_replace(' ', '_', $titulo)) . ".php";  // Ex: "pele_morreu.php"
        
        echo "
        <div style='border: 1px solid black; padding: 10px; margin: 10px;'>
            <h2>$titulo</h2>
            <p>$descricao</p>
            <img src='$imagem' alt='Imagem da Notícia' style='width: 100px; height: auto;'>
            <a href='$pagina_noticia'>Ler mais</a>
        </div>";
    }
} else {
    echo "Nenhuma notícia encontrada.";
}
?>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $siteName; ?></title>
    <style>
/* Reset básico */
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
}

/* Cabeçalho e navegação */
header {
    background-color: #d71920;
    color: #fff;
    padding: 10px 0;
    text-align: center;
    font-size: 24px;
    font-weight: bold;
}

nav {
    background-color: #b7161b;
    padding: 10px;
    text-align: center;
}

nav a {
    color: #fff;
    text-decoration: none;
    margin: 0 15px;
    font-weight: bold;
}

nav a:hover {
    text-decoration: underline;
}

/* Container principal */
.container {
    width: 90%;
    max-width: 1200px;
    margin: 20px auto;
    display: flex;
    gap: 20px;
}

/* Seção de notícias principais */
.main-news {
    flex: 3;
}

.main-news article {
    background-color: #fff;
    margin-bottom: 20px;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.main-news article img {
    max-width: 100%;
    border-radius: 5px;
}

.main-news article h2 {
    font-size: 22px;
    color: #d71920;
    margin-bottom: 10px;
}

.main-news article p {
    margin: 10px 0;
    color: #666;
}

.main-news article small {
    color: #999;
}

/* Seção lateral - Notícias recomendadas */
.sidebar {
    flex: 1;
}

.sidebar .recommended-news {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.sidebar .recommended-news h3 {
    color: #d71920;
    font-size: 18px;
    margin-bottom: 15px;
}

.sidebar .recommended-news article {
    margin-bottom: 15px;
}

.sidebar .recommended-news article h4 {
    font-size: 16px;
    color: #333;
    margin-bottom: 5px;
}

.sidebar .recommended-news article small {
    color: #999;
}

.sidebar .recommended-news article img {
    max-width: 100%;
    border-radius: 5px;
    margin-bottom: 10px;
}

/* Botões e interatividade */
button {
    background-color: #d71920;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

button:hover {
    background-color: #b7161b;
}

/* Rodapé */
footer {
    background-color: #333;
    color: #fff;
    padding: 10px 0;
    text-align: center;
    font-size: 14px;
    margin-top: 20px;
}
        </style>
</head>


