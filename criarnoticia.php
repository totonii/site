<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Notícia</title>
    <style>
        /* Estilos básicos para o layout */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        form {
            width: 100%;
            max-width: 800px;
            margin: auto;
        }
        .container {
            display: flex;
            flex-direction: column;
        }
        .container > div {
            margin-bottom: 10px;
        }
        .dynamic-box {
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h1>Criar Notícia</h1>

<form action="criarnoticia.php" method="post" enctype="multipart/form-data">
    <!-- Preview Section -->
    <div class="container">
        <div>
            <label for="titulo">Título da Notícia:</label><br>
            <input type="text" id="titulo" name="titulo" required><br><br>
        </div>
        <div>
            <label for="descricao">Descrição:</label><br>
            <textarea id="descricao" name="descricao" required></textarea><br><br>
        </div>
        <div>
            <label for="imagem">Imagem de Fundo:</label><br>
            <input type="file" id="imagem" name="imagem"><br><br>
        </div>
    </div>

    <!-- Dynamic Content Section for the dedicated page -->
    <h2>Conteúdo da Notícia</h2>
    <div id="dynamic-content"></div>
    <button type="button" onclick="addTextBox()">Adicionar Caixa de Texto</button>
    <button type="button" onclick="addImageBox()">Adicionar Caixa de Imagem</button>
    <button type="button" onclick="removeLastBox()">Apagar Última Caixa</button><br><br>

    <button type="submit">Criar Notícia</button>
</form>

<script>
    let contentCounter = 0;

    function addTextBox() {
        contentCounter++;
        const container = document.getElementById('dynamic-content');
        const textBox = document.createElement('div');
        textBox.classList.add('dynamic-box');
        textBox.innerHTML = `<label for="text-box-${contentCounter}">Texto ${contentCounter}:</label>
                             <textarea id="text-box-${contentCounter}" name="content[]"></textarea>`;
        container.appendChild(textBox);
    }

    function addImageBox() {
        contentCounter++;
        const container = document.getElementById('dynamic-content');
        const imageBox = document.createElement('div');
        imageBox.classList.add('dynamic-box');
        imageBox.innerHTML = `<label for="image-box-${contentCounter}">Imagem ${contentCounter}:</label>
                              <input type="file" id="image-box-${contentCounter}" name="images[]">`;
        container.appendChild(imageBox);
    }

    function removeLastBox() {
        const container = document.getElementById('dynamic-content');
        if (container.lastChild) {
            container.removeChild(container.lastChild);
            contentCounter--;
        }
    }
</script>

<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "testedebancodedados");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $imagemPreview = ''; // Imagem de fundo para o preview

    // Verificar e salvar imagem de fundo do preview
    if (!empty($_FILES['imagem']['name'])) {
        $imagemPreview = 'uploads/' . basename($_FILES['imagem']['name']);
        move_uploaded_file($_FILES['imagem']['tmp_name'], $imagemPreview);
    }

    // Inserir dados da notícia (preview)
    $sql = "INSERT INTO noticias (titulo, descricao, imagem) VALUES ('$titulo', '$descricao', '$imagemPreview')";
    if ($conn->query($sql) === TRUE) {
        $lastInsertedId = $conn->insert_id;  // Pega o ID da notícia recém-criada

        // Manipular caixas de texto e imagem dinâmicas
        foreach ($_POST['content'] as $key => $text) {
            $conn->query("INSERT INTO conteudos (noticia_id, tipo, conteudo) VALUES ('$lastInsertedId', 'texto', '$text')");
        }

        foreach ($_FILES['images']['name'] as $key => $imageName) {
            if (!empty($imageName)) {
                $imagePath = 'uploads/' . basename($imageName);
                move_uploaded_file($_FILES['images']['tmp_name'][$key], $imagePath);
                $conn->query("INSERT INTO conteudos (noticia_id, tipo, conteudo) VALUES ('$lastInsertedId', 'imagem', '$imagePath')");
            }
        }

        // Criar a página da notícia
        $pageName = strtolower(str_replace(' ', '_', $titulo)) . '.php'; // Ex: "pele_morreu.php"
        $pageContent = "
        <?php
        // Conexão com o banco de dados
        \$conn = new mysqli('localhost', 'root', '', 'testedebancodedados');

        // Pega os dados da notícia
        \$sql = 'SELECT titulo, descricao, imagem FROM noticias WHERE id = $lastInsertedId';
        \$result = \$conn->query(\$sql);

        if (\$result->num_rows > 0) {
            \$row = \$result->fetch_assoc();
            \$titulo = \$row['titulo'];
            \$descricao = \$row['descricao'];
            \$imagem = \$row['imagem'];
        } else {
            echo 'Notícia não encontrada.';
            exit;
        }
        ?>
        <!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title><?php echo \$titulo; ?></title>
            <style>
                body { font-family: Arial, sans-serif; }
                h1 { color: #333; }
                .content { margin: 20px; }
            </style>
        </head>
        <body>
            <div class='content'>
                <h1><?php echo \$titulo; ?></h1>
                <p><?php echo \$descricao; ?></p>
                <img src='<?php echo \$imagem; ?>' alt='Imagem da Notícia' style='max-width: 100%; height: auto;'>
            </div>
        </body>
        </html>";
        
        file_put_contents($pageName, $pageContent);

        echo "Notícia criada com sucesso!";
    } else {
        echo "Erro ao criar a notícia: " . $conn->error;
    }
}
?>


</body>
</html>
