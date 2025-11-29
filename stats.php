<?php
require_once 'session.php';
require_once 'theme_helper.php';
$abra = getPokemon();
$history = $_SESSION['training_history'] ?? [];

$statsOverTime = [];
$currentStats = [
    'level' => 5,
    'hp' => 25,
    'attack' => 20,
    'defense' => 15,
    'special_attack' => 105,
    'special_defense' => 55,
    'speed' => 90
];
$statsOverTime[] = [
    'session' => 0,
    'level' => $currentStats['level'],
    'hp' => $currentStats['hp'],
    'attack' => $currentStats['attack'],
    'defense' => $currentStats['defense'],
    'special_attack' => $currentStats['special_attack'],
    'special_defense' => $currentStats['special_defense'],
    'speed' => $currentStats['speed']
];
foreach ($history as $index => $log) {
    $statsOverTime[] = [
        'session' => $index + 1,
        'level' => $log['level_after'],
        'hp' => $log['hp_after'],
        'attack' => $log['stats_after']['attack'],
        'defense' => $log['stats_after']['defense'],
        'special_attack' => $log['stats_after']['special_attack'],
        'special_defense' => $log['stats_after']['special_defense'],
        'speed' => $log['stats_after']['speed']
    ];
}
$trainingTypes = [];
foreach ($history as $log) {
    $type = $log['training_type'];
    if (!isset($trainingTypes[$type])) {
        $trainingTypes[$type] = 0;
    }
    $trainingTypes[$type]++;
}

$totalXpGained = array_sum(array_column($history, 'xp_gain'));
$averageXpPerSession = count($history) > 0 ? round($totalXpGained / count($history), 1) : 0;

$evolutionProgress = [
    ['name' => 'Abra', 'level' => 5, 'reached' => $abra->getLevel() >= 5],
    ['name' => 'Kadabra', 'level' => 16, 'reached' => $abra->getLevel() >= 16],
    ['name' => 'Alakazam', 'level' => 36, 'reached' => $abra->getLevel() >= 36]
];
$themeStyles = getThemeStyles();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stats & Analytics - <?= htmlspecialchars($abra->getDisplayName()) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Press Start 2P', cursive;
            background: url('<?= $themeStyles['background_url'] ?>') no-repeat center center fixed;
            background-size: cover;
            color: white;
            margin: 0;
            padding: 0;
            font-size: <?= $themeStyles['font_size'] ?>;
        }
        .panel {
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            border: 2px solid <?= $themeStyles['primary_color'] ?>;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 0 20px <?= $themeStyles['primary_color'] ?>33;
        }
        .stat-card {
            background: rgba(30, 41, 59, 0.8);
            border-radius: 8px;
            padding: 12px;
            border: 1px solid <?= $themeStyles['primary_color'] ?>4d;
        }
    </style>
</head>
<body class="min-h-screen p-4">
<div class="max-w-6xl mx-auto">
    
    <a href="index.php" class="inline-block mb-4 text-sm text-gray-400">&larr; Kembali</a>
    <div class="panel mb-6">
        <h1 class="text-2xl text-center mb-6" style="color: <?= $themeStyles['primary_color'] ?>">Statistik & Analisa</h1>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="stat-card text-center">
                <div class="text-3xl mb-2">🎯</div>
                <div class="text-xs text-gray-400">Total Sessions</div>
                <div class="text-xl text-yellow-300"><?= count($history) ?></div>
            </div>
            <div class="stat-card text-center">
                <div class="text-3xl mb-2">⭐</div>
                <div class="text-xs text-gray-400">Current Level</div>
                <div class="text-xl text-green-300"><?= $abra->getLevel() ?></div>
            </div>
            <div class="stat-card text-center">
                <div class="text-3xl mb-2">💪</div>
                <div class="text-xs text-gray-400">Total XP Gained</div>
                <div class="text-xl text-purple-300"><?= $totalXpGained ?></div>
            </div>
            <div class="stat-card text-center">
                <div class="text-3xl mb-2">📈</div>
                <div class="text-xs text-gray-400">Avg XP/Session</div>
                <div class="text-xl text-orange-300"><?= $averageXpPerSession ?></div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-sm font-bold mb-3" style="color: <?= $themeStyles['primary_color'] ?>">🔄 Evolution Progress</h3>
            <div class="flex justify-between items-center">
                <?php foreach ($evolutionProgress as $index => $milestone): ?>
                <div class="flex flex-col items-center flex-1">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mb-2 <?= $milestone['reached'] ? 'bg-green-600' : 'bg-gray-700' ?>">
                        <?= $milestone['reached'] ? '✓' : '○' ?>
                    </div>
                    <div class="text-xs text-center">
                        <div class="<?= $milestone['reached'] ? 'text-green-400' : 'text-gray-500' ?>">
                            <?= htmlspecialchars($milestone['name']) ?>
                        </div>
                        <div class="text-gray-400">Lv. <?= $milestone['level'] ?></div>
                    </div>
                </div>
                <?php if ($index < count($evolutionProgress) - 1): ?>
                <div class="flex-1 h-1 mx-2 <?= $evolutionProgress[$index + 1]['reached'] ? 'bg-green-600' : 'bg-gray-700' ?>"></div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div class="stat-card">
                <h3 class="text-sm font-bold mb-3" style="color: <?= $themeStyles['primary_color'] ?>">Stats Growth Over Time</h3>
                <canvas id="statsGrowthChart"></canvas>
            </div>
            <div class="stat-card">
                <h3 class="text-sm font-bold mb-3" style="color: <?= $themeStyles['primary_color'] ?>">Training Type Distribution</h3>
                <canvas id="trainingTypePieChart"></canvas>
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="stat-card">
                <h3 class="text-sm font-bold mb-3" style="color: <?= $themeStyles['primary_color'] ?>">Current Stats Radar</h3>
                <canvas id="statsRadarChart"></canvas>
            </div>
            <div class="stat-card">
                <h3 class="text-sm font-bold mb-3" style="color: <?= $themeStyles['primary_color'] ?>">Stats Comparison</h3>
                <canvas id="statsBarChart"></canvas>
            </div>
        </div>

        <?php if (!empty($history)): ?>
        <div class="mt-6 stat-card">
            <h3 class="text-sm font-bold mb-3" style="color: <?= $themeStyles['primary_color'] ?>">Recent Training Sessions</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-700">
                            <th class="text-left p-2">Session</th>
                            <th class="text-left p-2">Type</th>
                            <th class="text-left p-2">Intensity</th>
                            <th class="text-left p-2">Level</th>
                            <th class="text-left p-2">XP Gained</th>
                            <th class="text-left p-2">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice(array_reverse($history), 0, 10) as $index => $log): ?>
                        <tr class="border-b border-gray-800 hover:bg-gray-700/50">
                            <td class="p-2">#<?= count($history) - $index ?></td>
                            <td class="p-2 text-yellow-300"><?= htmlspecialchars($log['training_type']) ?></td>
                            <td class="p-2"><?= $log['intensity'] ?></td>
                            <td class="p-2 text-green-300"><?= $log['level_after'] ?></td>
                            <td class="p-2 text-purple-300">+<?= $log['xp_gain'] ?></td>
                            <td class="p-2 text-gray-400"><?= date('M d, H:i', strtotime($log['timestamp'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php else: ?>
        <div class="mt-6 stat-card text-center py-12 text-gray-500">
            Belum ada data training. Mulai latihan untuk melihat analytics!
        </div>
        <?php endif; ?>
    </div>
    <div class="text-center">
        <a href="index.php" class="inline-block py-2 px-6 bg-gray-700 rounded hover:bg-gray-600">Kembali ke Beranda</a>
    </div>
</div>

<script>
const statsData = <?= json_encode($statsOverTime) ?>;
const trainingTypes = <?= json_encode($trainingTypes) ?>;

const ctx1 = document.getElementById('statsGrowthChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: statsData.map(d => `S${d.session}`),
        datasets: [
            {
                label: 'Level',
                data: statsData.map(d => d.level),
                borderColor: 'rgb(250, 204, 21)',
                backgroundColor: 'rgba(250, 204, 21, 0.1)',
                tension: 0.4
            },
            {
                label: 'HP',
                data: statsData.map(d => d.hp),
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4
            },
            {
                label: 'Sp.Atk',
                data: statsData.map(d => d.special_attack),
                borderColor: 'rgb(168, 85, 247)',
                backgroundColor: 'rgba(168, 85, 247, 0.1)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                labels: { color: 'white', font: { size: 10 } }
            }
        },
        scales: {
            x: { ticks: { color: 'white', font: { size: 8 } } },
            y: { ticks: { color: 'white', font: { size: 8 } } }
        }
    }
});

const ctx2 = document.getElementById('trainingTypePieChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: Object.keys(trainingTypes),
        datasets: [{
            data: Object.values(trainingTypes),
            backgroundColor: [
                'rgba(168, 85, 247, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(34, 197, 94, 0.8)',
                'rgba(239, 68, 68, 0.8)'
            ],
            borderColor: 'rgba(255, 255, 255, 0.2)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                labels: { color: 'white', font: { size: 10 } }
            }
        }
    }
});

const ctx3 = document.getElementById('statsRadarChart').getContext('2d');
const currentStats = statsData[statsData.length - 1];
new Chart(ctx3, {
    type: 'radar',
    data: {
        labels: ['Attack', 'Defense', 'Sp.Atk', 'Sp.Def', 'Speed', 'HP'],
        datasets: [{
            label: '<?= htmlspecialchars($abra->getDisplayName()) ?>',
            data: [
                currentStats.attack,
                currentStats.defense,
                currentStats.special_attack,
                currentStats.special_defense,
                currentStats.speed,
                currentStats.hp
            ],
            backgroundColor: 'rgba(56, 189, 248, 0.2)',
            borderColor: 'rgb(56, 189, 248)',
            pointBackgroundColor: 'rgb(56, 189, 248)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(56, 189, 248)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                labels: { color: 'white', font: { size: 10 } }
            }
        },
        scales: {
            r: {
                ticks: { color: 'white', backdropColor: 'transparent' },
                grid: { color: 'rgba(255, 255, 255, 0.1)' },
                pointLabels: { color: 'white', font: { size: 9 } }
            }
        }
    }
});

const ctx4 = document.getElementById('statsBarChart').getContext('2d');
new Chart(ctx4, {
    type: 'bar',
    data: {
        labels: ['Attack', 'Defense', 'Sp.Atk', 'Sp.Def', 'Speed'],
        datasets: [{
            label: 'Current Stats',
            data: [
                currentStats.attack,
                currentStats.defense,
                currentStats.special_attack,
                currentStats.special_defense,
                currentStats.speed
            ],
            backgroundColor: [
                'rgba(239, 68, 68, 0.7)',
                'rgba(59, 130, 246, 0.7)',
                'rgba(168, 85, 247, 0.7)',
                'rgba(236, 72, 153, 0.7)',
                'rgba(34, 197, 94, 0.7)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                labels: { color: 'white', font: { size: 10 } }
            }
        },
        scales: {
            x: { ticks: { color: 'white', font: { size: 9 } } },
            y: { ticks: { color: 'white', font: { size: 9 } } }
        }
    }
});
</script>
</body>
</html>