<?php

namespace App\Http\Controllers;

use Nette\Utils\Finder;

class MetricsController extends Controller
{
    public function index()
    {
        // Mémoire
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');

        // CPU (Charge moyenne)
        $cpuLoad = sys_getloadavg();
        $numCores = shell_exec('nproc');
        $isOverloaded = $cpuLoad[0] > $numCores;
        

        // Espace disque
        $diskFree = disk_free_space('/');
        $diskTotal = disk_total_space('/');

        return view('metrics.index', [
            'memoryUsage' => $memoryUsage,
            'memoryLimit' => $memoryLimit,
            'cpuLoad' => $cpuLoad,
            'diskFree' => $diskFree,
            'diskTotal' => $diskTotal,
            'numCores' => $numCores,
            'isOverloaded' => $isOverloaded,
        ]);
    }

    public function getMetrics()
    {
        $metrics = $this->getSystemMetrics();
        return response()->json($metrics);
    }

    // Fonction pour récupérer les métriques système
    /*private function getSystemMetrics()
    {
        // Charge CPU
        $cpuLoad = sys_getloadavg();

        // Mémoire
        $memInfo = file_get_contents("/proc/meminfo");
        preg_match('/MemTotal:\s+(\d+) kB/', $memInfo, $totalMemory);
        preg_match('/MemAvailable:\s+(\d+) kB/', $memInfo, $availableMemory);
        $usedMemory = ($totalMemory[1] - $availableMemory[1]) / 1024; // En Mo
        $totalMemory = $totalMemory[1] / 1024; // En Mo

        // Stockage
        $diskFree = disk_free_space("/") / (1024 * 1024 * 1024); // En Go
        $diskTotal = disk_total_space("/") / (1024 * 1024 * 1024); // En Go

        // Réseau (exemple simple avec ifconfig/statistiques réseau)
        $networkStats = shell_exec("ifconfig eth0 | grep 'RX packets'");
        preg_match('/RX packets (\d+)/', $networkStats, $rxPackets);

        return [
            'cpu_load' => $cpuLoad,
            'memory_used' => $usedMemory,
            'memory_total' => $totalMemory,
            'disk_free' => $diskFree,
            'disk_total' => $diskTotal,
            'network_rx_packets' => $rxPackets[1] ?? 0,
        ];
    }*/
}
