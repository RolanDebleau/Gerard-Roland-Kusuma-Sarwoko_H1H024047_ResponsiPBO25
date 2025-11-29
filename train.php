<?php
require_once 'session.php';
require_once 'theme_helper.php';
$abra = getPokemon();
$message = '';
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trainingType = trim($_POST['training_type'] ?? 'Mental Focus');
    $intensity = max(1, min(100, (int)($_POST['intensity'] ?? 10)));
    $result = $abra->train($trainingType, $intensity);
    savePokemon($abra);
    $_SESSION['training_history'][] = [
        'training_type' => $trainingType,
        'intensity' => $intensity,
        'level_before' => $result['before_level'],
        'level_after' => $result['after_level'],
        'hp_before' => $result['before_hp'],
        'hp_after' => $result['after_hp'],
        'xp_gain' => $result['xp_gain'],
        'stats_before' => $result['before_stats'],
        'stats_after' => $result['after_stats'],
        'timestamp' => date('Y-m-d H:i:s')
    ];
    $message = "✅ Latihan '{$trainingType}' (intensitas: {$intensity}) berhasil!";
    if ($result['evolved']) {
        $message .= " " . $result['evolved'];
    }
}
$info = $abra->getInfo();
$xpProgress = $info['xp_to_next_level'] > 0 
    ? min(100, ($info['xp'] / $info['xp_to_next_level']) * 100) 
    : 0;
$hpProgress = $abra->getMaxHp() > 0 
    ? min(100, ($info['hp'] / $abra->getMaxHp()) * 100) 
    : 0;
$themeStyles = getThemeStyles();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan <?= htmlspecialchars($abra->getDisplayName()) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        .glow { text-shadow: 0 0 8px rgba(255,255,255,0.7); }
        .panel {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            border: 2px solid <?= $themeStyles['primary_color'] ?>;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 0 20px <?= $themeStyles['primary_color'] ?>33;
        }
        .stat-bar, .xp-bar, .hp-bar {
            height: 8px;
            background: #1e293b;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 2px;
        }
        .stat-fill {
            height: 100%;
            background: linear-gradient(to right, <?= $themeStyles['primary_color'] ?>, <?= $themeStyles['secondary_color'] ?>);
            transition: width 0.3s ease;
        }
        .xp-fill {
            height: 100%;
            background: linear-gradient(to right, #f59e0b, #fbbf24);
            transition: width 0.3s ease;
        }
        .hp-fill {
            height: 100%;
            background: linear-gradient(to right, #22c55e, #10b981);
            transition: width 0.3s ease;
        }
        .new-skill-badge {
            background: linear-gradient(135deg, <?= $themeStyles['primary_color'] ?>, <?= $themeStyles['secondary_color'] ?>);
            animation: glow-pulse 2s ease-in-out infinite;
        }
        @keyframes glow-pulse {
            0%, 100% { box-shadow: 0 0 10px <?= $themeStyles['primary_color'] ?>80; }
            50% { box-shadow: 0 0 20px <?= $themeStyles['primary_color'] ?>ff; }
        }
    </style>
</head>
<body class="min-h-screen p-4">
<div class="max-w-4xl mx-auto">
    <a href="index.php" class="inline-block mb-4 text-sm text-gray-400">&larr; Kembali ke Beranda</a>

    <div class="panel mb-6">
        <h2 class="text-xl text-center mb-4 glow">Status <?= htmlspecialchars($abra->getDisplayName()) ?></h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="bg-gray-800/50 p-3 rounded-lg">
                <div class="flex justify-between text-sm mb-1">
                    <span>Level:</span>
                    <span class="text-yellow-300 font-bold"><?= $info['level'] ?></span>
                </div>
                <div class="text-xs text-gray-400">
                    XP: <?= $info['xp'] ?> / <?= $info['xp_to_next_level'] ?>
                </div>
                <div class="xp-bar">
                    <div class="xp-fill" style="width: <?= $xpProgress ?>%"></div>
                </div>
                <div class="text-xs text-right mt-1 text-yellow-300">
                    <?= number_format($xpProgress, 1) ?>% to next level
                </div>
            </div>

            <div class="bg-gray-800/50 p-3 rounded-lg">
                <div class="flex justify-between text-sm mb-1">
                    <span>HP:</span>
                    <span class="text-green-300 font-bold"><?= $info['hp'] ?> / <?= $info['max_hp'] ?></span>
                </div>
                <div class="hp-bar">
                    <div class="hp-fill" style="width: <?= $hpProgress ?>%"></div>
                </div>
                <div class="text-xs text-right mt-1 text-green-300">
                    <?= number_format($hpProgress, 1) ?>% HP
                </div>
            </div>
        </div>

        <div class="bg-gray-800/50 p-3 rounded-lg">
            <h3 class="text-sm font-bold text-purple-300 mb-2">🔄 Progress Evolusi:</h3>
            <div class="grid grid-cols-3 gap-2 text-xs text-center">
                <div class="<?= $info['level'] >= 5 ? 'text-green-400' : 'text-gray-500' ?>">
                    <?= $info['level'] >= 5 ? '✓' : '○' ?> Abra (Lv 5)
                </div>
                <div class="<?= $info['level'] >= 16 ? 'text-green-400' : 'text-gray-500' ?>">
                    <?= $info['level'] >= 16 ? '✓' : '○' ?> Kadabra (Lv 16)
                </div>
                <div class="<?= $info['level'] >= 36 ? 'text-green-400' : 'text-gray-500' ?>">
                    <?= $info['level'] >= 36 ? '✓' : '○' ?> Alakazam (Lv 36)
                </div>
            </div>
            <?php if ($info['level'] < 16): ?>
                <div class="text-xs text-center mt-2 text-gray-400">
                    Butuh <?= 16 - $info['level'] ?> level lagi untuk evolusi pertama!
                </div>
            <?php elseif ($info['level'] < 36): ?>
                <div class="text-xs text-center mt-2 text-gray-400">
                    Butuh <?= 36 - $info['level'] ?> level lagi untuk evolusi akhir!
                </div>
            <?php else: ?>
                <div class="text-xs text-center mt-2 text-green-400">
                    ✨ Evolusi maksimal tercapai!
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="panel mb-6">
        <h1 class="text-2xl text-center mb-6 glow">Latih <?= htmlspecialchars($abra->getDisplayName()) ?></h1>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1">Jenis Latihan:</label>
                <select name="training_type" class="w-full p-2 bg-gray-700 rounded text-sm" required>
                    <option value="Mental Focus">⭐ Mental Focus (Terbaik untuk Psychic - 1.8x XP)</option>
                    <option value="Speed">⚡ Speed Training (1.0x XP)</option>
                    <option value="Defense">🛡️ Defense Training (0.9x XP)</option>
                    <option value="Attack">⚔️ Attack Training (0.6x XP)</option>
                </select>
                <div class="text-xs text-gray-400 mt-1">
                    💡 Tip: Mental Focus memberikan XP paling banyak untuk tipe Psychic!
                </div>
            </div>
            <div>
                <label class="block mb-1">Intensitas (1–100):</label>
                <input type="range" name="intensity" min="1" max="100" value="20" 
                    class="w-full" id="intensitySlider" oninput="updateIntensity(this.value)">
                <div class="flex justify-between text-xs">
                    <span>Ringan (1)</span>
                    <span id="intensityValue" class="text-yellow-300 font-bold">20</span>
                    <span>Maksimal (100)</span>
                </div>
                <div class="text-xs text-gray-400 mt-1">
                    Estimasi XP: <span id="estimatedXP" class="text-green-400">~30-60</span>
                </div>
            </div>
            <button type="submit"
                    class="w-full py-3 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 rounded-lg font-bold">
                🚀 Mulai Latihan!
            </button>
        </form>
    </div>

    <?php if ($message): ?>
    <div class="panel mb-6 border-green-500">
        <h3 class="text-lg font-bold text-green-300 mb-3"><?= htmlspecialchars($message) ?></h3>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm mb-4">
            <div class="bg-gray-800/50 p-2 rounded text-center">
                <p class="text-gray-400 text-xs">Level</p>
                <p class="text-yellow-300 font-bold">
                    <?= $result['before_level'] ?> → <?= $result['after_level'] ?>
                    <?php if ($result['level_gain'] > 0): ?>
                        <span class="text-green-400">(+<?= $result['level_gain'] ?>)</span>
                    <?php endif; ?>
                </p>
            </div>
            <div class="bg-gray-800/50 p-2 rounded text-center">
                <p class="text-gray-400 text-xs">HP</p>
                <p class="text-cyan-300 font-bold">
                    <?= $result['before_hp'] ?> → <?= $result['after_hp'] ?>
                </p>
            </div>
            <div class="bg-gray-800/50 p-2 rounded text-center">
                <p class="text-gray-400 text-xs">Max HP</p>
                <p class="text-green-300 font-bold">
                    <?= $result['before_max_hp'] ?> → <?= $result['after_max_hp'] ?>
                    <?php if ($result['hp_gain'] > 0): ?>
                        <span class="text-green-400">(+<?= $result['hp_gain'] ?>)</span>
                    <?php endif; ?>
                </p>
            </div>
            <div class="bg-gray-800/50 p-2 rounded text-center">
                <p class="text-gray-400 text-xs">XP Gained</p>
                <p class="text-yellow-300 font-bold">+<?= $result['xp_gain'] ?></p>
            </div>
        </div>

        <?php if (!empty($result['new_skills'])): ?>
        <div class="mb-4 p-4 new-skill-badge rounded-lg">
            <h3 class="text-lg font-bold mb-3 text-center">
                🎉 NEW SKILL<?= count($result['new_skills']) > 1 ? 'S' : '' ?> UNLOCKED! 🎉
            </h3>
            <div class="space-y-3">
                <?php foreach ($result['new_skills'] as $newSkill): ?>
                <div class="bg-black/40 p-3 rounded-lg backdrop-blur">
                    <div class="flex justify-between items-start mb-2">
                        <span class="font-bold text-yellow-300 text-lg">
                            ⚡ <?= htmlspecialchars($newSkill['skill']['name']) ?>
                        </span>
                        <span class="text-sm text-gray-300">Level <?= $newSkill['level'] ?></span>
                    </div>
                    <p class="text-sm mb-2"><?= htmlspecialchars($newSkill['skill']['description']) ?></p>
                    <div class="flex justify-between text-xs">
                        <span class="text-pink-300">Type: <?= htmlspecialchars($newSkill['skill']['type']) ?></span>
                        <?php if ($newSkill['skill']['power'] > 0): ?>
                            <span class="text-yellow-300">Power: <?= $newSkill['skill']['power'] ?></span>
                        <?php else: ?>
                            <span class="text-gray-300">Status Move</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="mt-4">
            <h3 class="text-sm font-bold text-cyan-300 mb-2">📈 Perubahan Statistik:</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
                <?php foreach ($result['after_stats'] as $stat => $value): ?>
                    <?php 
                        $before = $result['before_stats'][$stat];
                        $diff = $value - $before;
                        $statName = ucfirst(str_replace('_', ' ', $stat));
                    ?>
                    <div class="bg-gray-800/50 p-2 rounded">
                        <div class="text-gray-400"><?= $statName ?>:</div>
                        <div>
                            <?= $before ?> → <span class="text-yellow-300"><?= $value ?></span>
                            <?php if ($diff > 0): ?>
                                <span class="text-green-400">(+<?= $diff ?>)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="panel mb-6">
        <h3 class="font-bold text-cyan-300 mb-2">🌀 <?= htmlspecialchars($abra->getDisplayName()) ?>'s Special Move</h3>
        <p class="text-sm"><?= htmlspecialchars($abra->specialMove()) ?></p>
    </div>

    <div class="flex gap-3">
        <a href="index.php" class="flex-1 text-center py-2 bg-gray-700 rounded hover:bg-gray-600">Beranda</a>
        <a href="history.php" class="flex-1 text-center py-2 bg-purple-700 rounded hover:bg-purple-600">Riwayat</a>
    </div>
</div>

<script>
function updateIntensity(value) {
    document.getElementById('intensityValue').textContent = value;
    const minXP = Math.round(value * 1.5 * 0.6);
    const maxXP = Math.round(value * 1.5 * 1.8);
    document.getElementById('estimatedXP').textContent = `~${minXP}-${maxXP}`;
}
</script>
</body>
</html>