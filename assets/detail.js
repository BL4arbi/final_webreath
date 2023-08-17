let dataGenerationInterval;

const charts = {
    temperature: null,
    speed: null,
    passengers: null
};

function formatTime(dateTimeObject) {
    const date = new Date(dateTimeObject.date);
    return `${date.getUTCHours()}:${date.getUTCMinutes()}:${date.getUTCSeconds()}`;
}
function startDataGeneration(moduleId) {
    if (dataGenerationInterval) {
        clearInterval(dataGenerationInterval); // S'assure qu'il n'y a pas d'autres générateurs actifs
    }

    dataGenerationInterval = setInterval(function () {
        fetch(`/generate-data/${moduleId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(dataArray => {
                dataArray.forEach((dataSet, index) => {
                    let chartId;
                    switch (index) {
                        case 0:
                            chartId = 'temperatureChart';
                            break;
                        case 1:
                            chartId = 'speedChart';
                            break;
                        case 2:
                            chartId = 'passengerChart';
                            break;
                    }

                    dataSet.forEach(item => {
                        const time = formatTime(item.time);
                        updateChart(chartId, time, item.data);
                    });
                });
            })
            .catch(error => {
                console.error(`problème avec le  fetch : ${error.message}`);
            });
    }, 2000);
}
document.querySelectorAll('.enlarge-button').forEach(button => {
    button.addEventListener('click', function() {
        const target = this.getAttribute('data-target');
        const targetElement = document.getElementById(target);
        const parentDiv = targetElement.closest('.chart-container');

        if (parentDiv.classList.contains('expanded')) {
            parentDiv.classList.remove('expanded');
            this.textContent = 'Agrandir';
        } else {
            // Réduisez tous les graphiques
            document.querySelectorAll('.chart-container.expanded').forEach(chart => {
                chart.classList.remove('expanded');
            });
            document.querySelectorAll('.enlarge-button').forEach(btn => {
                btn.textContent = 'Agrandir';
            });

            // Agrandissez le graphique sélectionné
            parentDiv.classList.add('expanded');
            this.textContent = 'Réduire';
        }
    });
});

function initializeChart(chartId, label, data) {
    const ctx = document.getElementById(chartId).getContext('2d');
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: [label],
            datasets: [{
                label: chartId,
                data: [data],
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                fill: false
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function updateChart(chartId, label, data) {
    const chart = charts[chartId];
    if (!chart) {
        charts[chartId] = initializeChart(chartId, label, data);
    } else {
        if (chart.data.labels.length > 10) {
            chart.data.labels.shift();
            chart.data.datasets[0].data.shift();
        }
        chart.data.labels.push(label);
        chart.data.datasets[0].data.push(data);
        chart.update();
    }
}


document.querySelector('.stop-generation-button').addEventListener('click', function () {
    const moduleId = this.getAttribute('data-module-id');
    clearInterval(dataGenerationInterval);
    dataGenerationInterval = null;
    if (moduleId) {
        fetch(`/stopp/` + moduleId, {
            method: 'POST'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Pas de réponses réseaux');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);
                // Ici, vous pouvez ajouter du code pour afficher un message de succès à l'utilisateur
                // ou pour mettre à jour l'interface en conséquence.
            })
            .catch(error => {
                console.error(`problème avec le fetch: ${error.message}`);
            });
    }
});
window.addEventListener('load', function () {
    const url = window.location.href;
    const regex = /\/detail\/(\d+)/;
    const match = url.match(regex);

    if (match && match[1]) {
        startDataGeneration(match[1]);
    }
});
document.querySelector('.start-generation-button').addEventListener('click', function () {
    const moduleId = this.getAttribute('data-module-id');

    fetch(`/marche/${moduleId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log(data.message);

            // Relancez la génération de données
            startDataGeneration(moduleId);
        })
        .catch(error => {
            console.error(`problème avec le fetch: ${error.message}`);
        });
});

window.addEventListener('beforeunload', function () {
    clearInterval(dataGenerationInterval);
});



