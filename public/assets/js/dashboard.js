document.addEventListener('DOMContentLoaded', function () {
    const ringkasanChart = new Chart(document.getElementById('ringkasanChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Warga', 'Petugas', 'Armada', 'Laporan W'],
            datasets: [{
                data: window.ringkasanData,
                backgroundColor: '#28a745',
                barThickness: 20,
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 500
                    }
                },
                y: {
                    grid: { display: false }
                }
            }
        }
    });    

    new Chart(document.getElementById('pengangkutanChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: window.kendaraanLabels,
            datasets: window.pengangkutanDatasets.map((item, index) => ({
                label: item.label,
                data: item.data,
                backgroundColor: ['#007bff', '#20c997', '#28a745', '#dc3545', '#ffc107'][index % 5],
                barPercentage: 0.6,
                categoryPercentage: 0.8
            }))
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, max: 50 } }
        }
    });

    new Chart(document.getElementById('kategoriMasalahChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: window.kategoriLabels,
            datasets: [{
                data: window.kategoriData,
                backgroundColor: ['#28a745', '#ffc107', '#8B4513', '#f8f9fa', '#343a40'],
                borderWidth: 0
            }]
        },
        options: { plugins: { legend: { display: false } } }
    });
});
