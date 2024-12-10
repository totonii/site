<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "testedebancodedados");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $noticia_id = $_POST['noticia_id'];

    if (isset($_POST['adicionar_setor'])) {
        // Lógica para adicionar setor (adapte conforme sua necessidade)
        echo "Setor adicionado!";
    }

    if (isset($_POST['adicionar_imagem'])) {
        // Lógica para adicionar imagem
        echo "Imagem adicionada!";
    }

    if (isset($_POST['remover_noticia'])) {
        // Remover notícia do banco de dados
        $sql = "DELETE FROM noticias WHERE id = $noticia_id";
        if ($conn->query($sql) === TRUE) {
            echo "Notícia removida com sucesso!";
            // Opcional: Remover a página dedicada também
            unlink("noticia_$noticia_id.php");
        } else {
            echo "Erro ao remover a notícia: " . $conn->error;
        }
    }
}
?>
