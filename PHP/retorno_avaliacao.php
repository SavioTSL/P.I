<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'PROFESSOR') {
    header("Location: index.html");
    exit();
}

$search = isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retorno da Avaliação</title>
    <link rel="stylesheet" href="../CSS/retorno_avaliacao.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
    <button class="back-button" onclick="window.history.back()">
    <i class="fas fa-arrow-left"></i> Voltar
    </button>
        <h1>Retorno da Avaliação</h1>
        <div class="table-container" id="table-container">
            <form id="search-form" action="" method="post">
                <input type="search" name="search" id="search-input" placeholder="Buscar por turma..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Buscar</button>
            </form>
            <div id="table-content">
                <?php include 'search.php';  ?>
            </div>
        </div>
        <div id="details" class="details-container"></div>
        <button class="print-button" onclick="window.print()">Imprimir</button>
        <button class="report-button" onclick="window.location.href='../HTML/relatorio.html'">Ver Relatório</button>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.detail-link', function(e) {
                e.preventDefault();
                var nome = $(this).data('nome');

                $.ajax({
                    url: 'get_details.php',
                    method: 'GET',
                    data: { nome: nome },
                    success: function(response) {
                        $('#details').html(response);
                    },
                    error: function(xhr, status, error) {
                        $('#details').html('<p>Erro ao carregar detalhes.</p>');
                    }
                });
            });

            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                var searchValue = $('#search-input').val();

                $.ajax({
                    url: 'search.php',
                    method: 'POST',
                    data: { search: searchValue },
                    success: function(response) {
                        $('#table-content').html(response);
                    },
                    error: function(xhr, status, error) {
                        $('#table-content').html('<p>Erro ao buscar dados.</p>');
                    }
                });
            });
        });
    </script>
</body>
</html>