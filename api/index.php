<?php include 'koneksi.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Greenhouse Monitoring Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-1/5 bg-blue-500 text-white p-4">
            <h1 class="text-2xl font-bold">Smart Greenhouse</h1>
            <nav class="mt-6">
                <ul>
                    <li class="mb-4"><a href="#" class="hover:text-gray-300">Dashboard</a></li>
                    <li class="mb-4"><a href="#" class="hover:text-gray-300">Kelompok D5</a></li>
                    <li class="mb-4"><a href="#" class="hover:text-gray-300">Viktorikus Nokia Laksamana Febrianto - 152023131</a></li>
                    <li class="mb-4"><a href="#" class="hover:text-gray-300">Farisy Ilman Syarif -152023135</a></li>
                    <li class="mb-4"><a href="#" class="hover:text-gray-300">Fikriansyah - 152023138</a></li>
                    <li class="mb-4"><a href="#" class="hover:text-gray-300">Reza Putra Pratama - 152023145</a></li>
                    <li class="mb-4"><a href="#" class="hover:text-gray-300">Rifki Ardiansyah Suhendi - 152023153</a></li>
                    <li class="mb-4"><a href="#" class="hover:text-gray-300">Rido Gusti Illahi - 152023169</a></li>
                    <li class="mb-4"><a href="#" class="hover:text-gray-300">Martin Rizki Wendi Sinurat - 152023152</a></li>
                    <li class="mb-4"><a href="#" class="hover:text-gray-300">Bakhitah firjatullah - 152023165</a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <!-- Header Cards -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded shadow-md">
                    <p class="text-gray-600">Temperature</p>
                    <h2 id="tempDisplay" class="text-2xl font-bold">-</h2>
                </div>
                <div class="bg-white p-4 rounded shadow-md">
                    <p class="text-gray-600">Humidity</p>
                    <h2 id="humidityDisplay" class="text-2xl font-bold">-</h2>
                </div>
                <div class="bg-white p-4 rounded shadow-md">
                    <p class="text-gray-600">Light Intensity</p>
                    <h2 id="lightDisplay" class="text-2xl font-bold">-</h2>
                </div>
                <div class="bg-white p-4 rounded shadow-md">
                    <p class="text-gray-600">Lamp Status</p>
                    <h2 id="lampDisplay" class="text-2xl font-bold text-green-500">-</h2>
                </div>
                
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded shadow-md">
                    <h3 class="text-lg font-semibold mb-4">Temperature Over Time</h3>
                    <canvas id="temperatureChart"></canvas>
                </div>
                <div class="bg-white p-4 rounded shadow-md">
                    <h3 class="text-lg font-semibold mb-4">Humidity Over Time</h3>
                    <canvas id="humidityChart"></canvas>
                </div>
                <div class="bg-white p-4 rounded shadow-md">
                    <h3 class="text-lg font-semibold mb-4">Light Intensity Over Time</h3>
                    <canvas id="lightIntensityChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fetch data from API and update dashboard
        async function fetchData() {
            const response = await fetch('api.php');
            const data = await response.json();

            if (data.length > 0) {
                const latest = data[0];

                // Update header cards
                document.getElementById('tempDisplay').textContent = `${latest.temperature}°C`;
                document.getElementById('humidityDisplay').textContent = `${latest.humidity}%`;
                document.getElementById('lightDisplay').textContent = `${latest.light_level} lux`;
                document.getElementById('lampDisplay').textContent = `${latest.lamp_status}`;

                // Update charts
                updateCharts(data);
            }
        }

        // Update charts with new data
        function updateCharts(data) {

            const latestData = data.sort((a, b) => b.id - a.id).slice(0, 10);

            const labels = latestData.map(entry => entry.id);
            const tempData = latestData.map(entry => entry.temperature);
            const humidityData = latestData.map(entry => entry.humidity);
            const lightData = latestData.map(entry => entry.light_level);

            temperatureChart.data.labels = labels;
            temperatureChart.data.datasets[0].data = tempData;
            temperatureChart.update();

            humidityChart.data.labels = labels;
            humidityChart.data.datasets[0].data = humidityData;
            humidityChart.update();

            lightIntensityChart.data.labels = labels;
            lightIntensityChart.data.datasets[0].data = lightData;
            lightIntensityChart.update();
        }

        // Initialize charts
        const temperatureChart = new Chart(document.getElementById('temperatureChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Temperature (°C)',
                    data: [],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { display: false, reverse: true},
                    y: { display: true }
                }
            }
        });

        const humidityChart = new Chart(document.getElementById('humidityChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Humidity (%)',
                    data: [],
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    fill: true,
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { display: false, reverse: true},
                    y: { display: true }
                }
            }
        });

        const lightIntensityChart = new Chart(document.getElementById('lightIntensityChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Light Intensity (lux)',
                    data: [],
                    borderColor: 'rgba(255, 206, 86, 1)',
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    fill: true,
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { display: false, reverse: true },
                    y: { display: true }
                }
            }
        });

        // Fetch data every 2 seconds
        fetchData();
        setInterval(fetchData, 2000);
    </script>
</body>
</html>
