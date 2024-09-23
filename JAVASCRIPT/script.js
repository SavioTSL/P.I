$(document).ready(function() {
    var myCharts = {}; // Objeto para armazenar os gráficos

    function createCanvas(fieldName) {
        var canvas = $('<canvas></canvas>').attr('id', fieldName + 'Chart');
        $('#chartsContainer').append(canvas);
    }

    function updateChart(fieldName, data) {
        var simCount = parseInt(data.sim_count) || 0;
        var naoCount = parseInt(data.nao_count) || 0;
        var parcialCount = parseInt(data.parcial_count) || 0;
        var naoAtendeuCount = parseInt(data.nao_atendeu_count) || 0;
    
        var totalCount = simCount + naoCount + parcialCount + naoAtendeuCount;
    
        var percentages = totalCount > 0 ? [
            (simCount / totalCount * 100).toFixed(2),
            (naoCount / totalCount * 100).toFixed(2),
            (parcialCount / totalCount * 100).toFixed(2),
            (naoAtendeuCount / totalCount * 100).toFixed(2)
        ] : [0, 0, 0, 0];
    
        var ctx = document.getElementById(fieldName + 'Chart');
    
        if (!ctx) {
            console.error(`Canvas não encontrado para ${fieldName}`);
            return;
        }
    
        if (myCharts[fieldName]) {
            myCharts[fieldName].destroy();
        }
    
        myCharts[fieldName] = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Sim', 'Não', 'Parcial', 'Não Atendeu'],
                datasets: [{
                    label: `${fieldName} - Porcentagem por Turma`,
                    data: [simCount, naoCount, parcialCount, naoAtendeuCount],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return `${fieldName} - ${tooltipItem.label}: ${tooltipItem.raw} (${percentages[tooltipItem.dataIndex]}%)`;
                            }
                        }
                    },
                    datalabels: {
                        color: '#36A2EB',
                        anchor: 'end',
                        align: 'start', // Alinha os rótulos
                        position: 'start',
                        formatter: function(value, context) {
                            return percentages[context.dataIndex] + '%';
                        },
                        font: {
                            size: 14, // Ajuste o tamanho da fonte
                        }
                    }
                }
            },
            plugins: [ChartDataLabels] // Adiciona o plugin de rótulos
        });
    }    

    function fetchData(turma) {
        $.ajax({
            url: '../PHP/detailed_report.php',
            type: 'GET',
            dataType: 'json',
            data: { turma: turma },
            success: function(response) {
                console.log("Dados recebidos:", response);
                if (response.error) {
                    console.error("Erro nos dados:", response.error);
                    return;
                }
                $('#chartsContainer').empty(); // Limpa o container antes de adicionar novos gráficos
                for (var field in response) {
                    createCanvas(field); // Cria um canvas para o campo
                    updateChart(field, response[field]);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro ao buscar dados:', error);
            }
        });
    }

    $('#turmaSearch').on('input', function() {
        var turma = $(this).val();
        fetchData(turma);
    });

    fetchData(''); // Chama ao carregar a página
});
