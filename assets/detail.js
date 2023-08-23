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
function resetOtherCharts(target) {
    document.querySelectorAll('.chart-container.expanded').forEach(chartContainer => {
        if (chartContainer !== target) {
            chartContainer.classList.remove('expanded');
            const enlargeButton = chartContainer.querySelector('.enlarge-button');
            const reduceButton = chartContainer.querySelector('.reduce-button');
            if (enlargeButton) enlargeButton.style.display = 'block';
            if (reduceButton) reduceButton.style.display = 'none';
        }
    });
}

document.querySelectorAll('.enlarge-button').forEach(button => {
    button.addEventListener('click', function() {
        const targetElement = document.getElementById(this.getAttribute('data-target')).closest('.chart-container');
        resetOtherCharts(targetElement);  // Réinitialisez les autres graphiques

        if (!targetElement.classList.contains('expanded')) {
            targetElement.classList.add('expanded');
            this.style.display = 'none';
            const associatedReduceButton = targetElement.querySelector('.reduce-button');
            if (associatedReduceButton) {
                associatedReduceButton.style.display = 'block';
            }

            toggleNavbar(true);  // Cache la navbar
        }
    });
});

document.querySelectorAll('.reduce-button').forEach(button => {
    button.addEventListener('click', function() {
        const targetElement = document.getElementById(this.getAttribute('data-target')).closest('.chart-container');
        if (targetElement.classList.contains('expanded')) {
            targetElement.classList.remove('expanded');
            this.style.display = 'none';
            const associatedEnlargeButton = targetElement.querySelector('.enlarge-button');
            if (associatedEnlargeButton) {
                associatedEnlargeButton.style.display = 'block';
            }

            toggleNavbar(false);  // Montre la navbar
        }
    });
});

function initializeChart(chartId, label, data) {
    const ctx = document.getElementById(chartId).getContext('2d');

    let borderColor;
    switch(chartId) {
        case 'temperatureChart':
            borderColor = 'red';
            break;
        case 'speedChart':
            borderColor = 'blue';
            break;
        case 'passengerChart':
            borderColor = 'green';
            break;
        default:
            borderColor = 'rgba(75, 192, 192, 1)'; // couleur par défaut
    }

    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: [label],
            datasets: [{
                label: chartId,
                data: [data],
                borderColor: borderColor,  // utilisation de la couleur définie précédemment
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

            // Relance la génération de données
            startDataGeneration(moduleId);
        })
        .catch(error => {
            console.error(`problème avec le fetch: ${error.message}`);
        });
});

window.addEventListener('beforeunload', function () {
    clearInterval(dataGenerationInterval);
});



