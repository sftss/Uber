<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Métriques Système</title>
</head>
<body>
    <h1>Métriques du système</h1>
    <ul>
        <li><strong>Utilisation de la mémoire :</strong> {{ round($memoryUsage / 1024 / 1024, 2) }} MB</li>
        <li>
            <strong>Limite mémoire :</strong>
            {{ $memoryLimit == -1 ? 'Illimité' : $memoryLimit }}
        </li>
        <li>
            <strong>Charge CPU :</strong> {{ implode(', ', $cpuLoad) }}
            <small>
                ({{ $isOverloaded ? 'Surchargé' : 'Stable' }} - {{ $numCores }} cœurs disponibles)
            </small>
        </li>

        <li><strong>Espace disque libre :</strong> {{ round($diskFree / 1024 / 1024 / 1024, 2) }} GB</li>
        <li><strong>Espace disque total :</strong> {{ round($diskTotal / 1024 / 1024 / 1024, 2) }} GB</li>
    </ul>

    <!-- Section pour afficher les métriques en temps réel -->
    <h2>Métriques en temps réel</h2>
    <ul>
        <li><strong>Charge CPU :</strong> <span id="cpu-load"></span></li>
        <li><strong>Mémoire utilisée :</strong> <span id="memory-used"></span> Mo / <span id="memory-total"></span> Mo</li>
        <li><strong>Espace disque libre :</strong> <span id="disk-free"></span> Go / <span id="disk-total"></span> Go</li>
        <li><strong>Packets réseau reçus :</strong> <span id="network-rx-packets"></span></li>
    </ul>

    <!-- Script pour récupérer et afficher les métriques en temps réel -->
    <script>
        function fetchMetrics() {
            fetch('/system-metrics')
                .then(response => response.json())
                .then(data => {
                    // Mettre à jour l'affichage des métriques
                    document.getElementById('cpu-load').textContent = data.cpu_load.join(', ');
                    document.getElementById('memory-used').textContent = data.memory_used.toFixed(2);
                    document.getElementById('memory-total').textContent = data.memory_total.toFixed(2);
                    document.getElementById('disk-free').textContent = data.disk_free.toFixed(2);
                    document.getElementById('disk-total').textContent = data.disk_total.toFixed(2);
                    document.getElementById('network-rx-packets').textContent = data.network_rx_packets;
                })
                .catch(error => console.error('Error fetching metrics:', error));
        }

        // Appeler la fonction fetchMetrics toutes les 5 secondes
        setInterval(fetchMetrics, 5000);

        // Appeler immédiatement pour charger les données au lancement
        fetchMetrics();
    </script>
</body>
</html>
