<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'PROFESSOR') {
    header("Location: ../index.html");
    exit();
}

if (!isset($_GET['nome']) || empty($_GET['nome'])) {
    echo '<p>Nome não fornecido.</p>';
    exit();
}

$servername = "localhost";
$username = "root";
$password = "123456"; 
$dbname = "avaliacao"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$nome = $_GET['nome'];

// Prepara e executa a consulta SQL
$stmt = $conn->prepare("SELECT * FROM avaliacoes WHERE nome = ?");
$stmt->bind_param("s", $nome);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Mapeamento dos nomes dos campos para nomes amigáveis
    $field_names = [
        'nome' => 'Nome do Aluno',
        'turma' => 'Turma',
        'docente_presenca' => 'Presença do Docente',
        'docente_pontualidade' => 'Pontualidade do Docente',
        'docente_comunicacao' => 'Comunicação do Docente',
        'docente_dominio' => 'Domínio do Docente',
        'docente_interesse' => 'Interesse do Docente',
        'docente_interacao' => 'Interação do Docente',
        'docente_avaliacao' => 'Avaliação do Docente',
        'curso_needs' => 'Necessidades do Curso',
        'curso_temas' => 'Temas do Curso',
        'curso_carga' => 'Carga Horária do Curso',
        'curso_relacao' => 'Relação Curso-Objetivos',
        'material_suficiente' => 'Material Suficiente',
        'instalacoes_satisfatorias' => 'Instalações Satisfatórias',
        'coordenacao_presente' => 'Coordenação Presente',
        'aluno_presenca' => 'Presença do Aluno',
        'aluno_pontualidade' => 'Pontualidade do Aluno',
        'aluno_motivacao' => 'Motivação do Aluno',
        'aluno_participacao' => 'Participação do Aluno',
        'aluno_aproveitamento' => 'Aproveitamento do Aluno',
        'aluno_capacidade' => 'Capacidade do Aluno',
        'comentarios' => 'Comentários',
        'curso_origem' => 'Origem do Curso'
    ];

    echo '<table class="data-table">';
    echo '<thead>';
    echo '<tr><th>Campo</th><th>Valor</th></tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($row as $key => $value) {
        // Usa o nome amigável se existir, caso contrário, usa o nome do campo original
        $display_name = isset($field_names[$key]) ? $field_names[$key] : htmlspecialchars($key);
        echo '<tr>';
        echo '<td>' . htmlspecialchars($display_name) . '</td>';
        echo '<td>' . htmlspecialchars($value) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p>Nenhum detalhe encontrado.</p>';
}

$stmt->close();
$conn->close();
?>
