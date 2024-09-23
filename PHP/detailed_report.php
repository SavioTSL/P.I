<?php
// Ativar exibição de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuração do banco de dados
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "avaliacao";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    http_response_code(500); // Erro interno do servidor
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Obtendo o parâmetro da turma, se fornecido e sanitizando
$turma = isset($_GET['turma']) ? $conn->real_escape_string($_GET['turma']) : '';

// Construindo a consulta SQL com uso de prepared statements para maior segurança
// Supondo que $turma pode ser uma string ou null
$sql = "
    SELECT 
        campo,
        SUM(sim_count) AS sim_count,
        SUM(nao_count) AS nao_count,
        SUM(parcial_count) AS parcial_count,
        SUM(nao_atendeu_count) AS nao_atendeu_count
    FROM (
        SELECT 
            'docente_presenca' AS campo,
            SUM(docente_presenca = 'Sim') AS sim_count,
            SUM(docente_presenca = 'Não') AS nao_count,
            SUM(docente_presenca = 'Parcial') AS parcial_count,
            SUM(docente_presenca = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'docente_pontualidade' AS campo,
            SUM(docente_pontualidade = 'Sim') AS sim_count,
            SUM(docente_pontualidade = 'Não') AS nao_count,
            SUM(docente_pontualidade = 'Parcial') AS parcial_count,
            SUM(docente_pontualidade = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'docente_comunicacao' AS campo,
            SUM(docente_comunicacao = 'Sim') AS sim_count,
            SUM(docente_comunicacao = 'Não') AS nao_count,
            SUM(docente_comunicacao = 'Parcial') AS parcial_count,
            SUM(docente_comunicacao = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'docente_dominio' AS campo,
            SUM(docente_dominio = 'Sim') AS sim_count,
            SUM(docente_dominio = 'Não') AS nao_count,
            SUM(docente_dominio = 'Parcial') AS parcial_count,
            SUM(docente_dominio = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'docente_interesse' AS campo,
            SUM(docente_interesse = 'Sim') AS sim_count,
            SUM(docente_interesse = 'Não') AS nao_count,
            SUM(docente_interesse = 'Parcial') AS parcial_count,
            SUM(docente_interesse = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'docente_interacao' AS campo,
            SUM(docente_interacao = 'Sim') AS sim_count,
            SUM(docente_interacao = 'Não') AS nao_count,
            SUM(docente_interacao = 'Parcial') AS parcial_count,
            SUM(docente_interacao = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'docente_avaliacao' AS campo,
            SUM(docente_avaliacao = 'Sim') AS sim_count,
            SUM(docente_avaliacao = 'Não') AS nao_count,
            SUM(docente_avaliacao = 'Parcial') AS parcial_count,
            SUM(docente_avaliacao = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'curso_needs' AS campo,
            SUM(curso_needs = 'Sim') AS sim_count,
            SUM(curso_needs = 'Não') AS nao_count,
            SUM(curso_needs = 'Parcial') AS parcial_count,
            SUM(curso_needs = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'curso_temas' AS campo,
            SUM(curso_temas = 'Sim') AS sim_count,
            SUM(curso_temas = 'Não') AS nao_count,
            SUM(curso_temas = 'Parcial') AS parcial_count,
            SUM(curso_temas = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'curso_carga' AS campo,
            SUM(curso_carga = 'Sim') AS sim_count,
            SUM(curso_carga = 'Não') AS nao_count,
            SUM(curso_carga = 'Parcial') AS parcial_count,
            SUM(curso_carga = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'curso_relacao' AS campo,
            SUM(curso_relacao = 'Sim') AS sim_count,
            SUM(curso_relacao = 'Não') AS nao_count,
            SUM(curso_relacao = 'Parcial') AS parcial_count,
            SUM(curso_relacao = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'material_suficiente' AS campo,
            SUM(material_suficiente = 'Sim') AS sim_count,
            SUM(material_suficiente = 'Não') AS nao_count,
            SUM(material_suficiente = 'Parcial') AS parcial_count,
            SUM(material_suficiente = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'instalacoes_satisfatorias' AS campo,
            SUM(instalacoes_satisfatorias = 'Sim') AS sim_count,
            SUM(instalacoes_satisfatorias = 'Não') AS nao_count,
            SUM(instalacoes_satisfatorias = 'Parcial') AS parcial_count,
            SUM(instalacoes_satisfatorias = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'coordenacao_presente' AS campo,
            SUM(coordenacao_presente = 'Sim') AS sim_count,
            SUM(coordenacao_presente = 'Não') AS nao_count,
            SUM(coordenacao_presente = 'Parcial') AS parcial_count,
            SUM(coordenacao_presente = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'aluno_presenca' AS campo,
            SUM(aluno_presenca = 'Sim') AS sim_count,
            SUM(aluno_presenca = 'Não') AS nao_count,
            SUM(aluno_presenca = 'Parcial') AS parcial_count,
            SUM(aluno_presenca = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'aluno_pontualidade' AS campo,
            SUM(aluno_pontualidade = 'Sim') AS sim_count,
            SUM(aluno_pontualidade = 'Não') AS nao_count,
            SUM(aluno_pontualidade = 'Parcial') AS parcial_count,
            SUM(aluno_pontualidade = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'aluno_motivacao' AS campo,
            SUM(aluno_motivacao = 'Sim') AS sim_count,
            SUM(aluno_motivacao = 'Não') AS nao_count,
            SUM(aluno_motivacao = 'Parcial') AS parcial_count,
            SUM(aluno_motivacao = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'aluno_participacao' AS campo,
            SUM(aluno_participacao = 'Sim') AS sim_count,
            SUM(aluno_participacao = 'Não') AS nao_count,
            SUM(aluno_participacao = 'Parcial') AS parcial_count,
            SUM(aluno_participacao = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'aluno_capacidade' AS campo,
            SUM(aluno_capacidade = 'Sim') AS sim_count,
            SUM(aluno_capacidade = 'Não') AS nao_count,
            SUM(aluno_capacidade = 'Parcial') AS parcial_count,
            SUM(aluno_capacidade = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
        UNION ALL
        SELECT 
            'aluno_aproveitamento' AS campo,
            SUM(aluno_aproveitamento = 'Sim') AS sim_count,
            SUM(aluno_aproveitamento = 'Não') AS nao_count,
            SUM(aluno_aproveitamento = 'Parcial') AS parcial_count,
            SUM(aluno_aproveitamento = 'Não Atendeu') AS nao_atendeu_count
        FROM avaliacoes " . ($turma ? "WHERE turma = ?" : "") . "
    ) AS subquery
GROUP BY campo
";

// Preparar a consulta
$stmt = $conn->prepare($sql);

// Contar o número de parâmetros `?`
$param_count = substr_count($sql, '?');

// Se $turma está definido e não é nulo, vincule o número correto de parâmetros
if ($turma) {
    $params = array_fill(0, $param_count, $turma);
    $types = str_repeat('s', $param_count); // 's' para string, ajuste se os parâmetros forem de outro tipo
    $stmt->bind_param($types, ...$params);
}

// Executar a consulta
$stmt->execute();

// Obter resultados
$result = $stmt->get_result();


// Preparar a declaração
$stmt = $conn->prepare($sql);

// Se $turma está definido e não é nulo, vincule o número correto de parâmetros
if ($turma) {
    $params = array_fill(0, $param_count, $turma);
    $types = str_repeat('s', $param_count); // 's' para string
    $stmt->bind_param($types, ...$params);
}

// Executar a consulta
$stmt->execute();

// Obter resultados
$result = $stmt->get_result();


// Verificar erros na execução da consulta
if ($stmt->error) {
    http_response_code(500); // Erro interno do servidor
    echo json_encode(['error' => 'Query error: ' . $stmt->error]);
    exit;
}

// Criar array para armazenar os dados
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[$row['campo']] = $row;
    }
} else {
    $data = ['message' => 'Nenhum resultado encontrado.'];
}

// Converter os dados para JSON
header('Content-Type: application/json');
echo json_encode($data);

// Fechar a declaração e a conexão
$stmt->close();
$conn->close();
?>
    