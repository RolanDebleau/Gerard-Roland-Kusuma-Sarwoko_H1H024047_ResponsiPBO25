<?php
require_once 'session.php';
require_once 'theme_helper.php';

$abra = getPokemon();
$history = $_SESSION['training_history'] ?? [];

$themeStyles = getThemeStyles(); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Latihan</title>
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
        .glow { 
            text-shadow: 0 0 8px rgba(255,255,255,0.7); 
        }
        .panel {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            border: 2px solid <?= $themeStyles['primary_color'] ?>;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 0 20px <?= $themeStyles['primary_color'] ?>33;
        }
        .history-card {
            background: rgba(30, 41, 59, 0.7);
            border-left: 4px solid <?= $themeStyles['primary_color'] ?>;
        }
        .btn-back {
            background: rgba(55, 65, 81, 0.8);
            border: 1px solid <?= $themeStyles['primary_color'] ?>4d;
        }
        .btn-back:hover {
            background: rgba(75, 85, 99, 0.9);
            border-color: <?= $themeStyles['primary_color'] ?>;
        }
    </style>
</head>
<body class="min-h-screen p-4">
<div class="max-w-3xl mx-auto">

    <a href="index.php" class="inline-block mb-4 text-sm text-gray-400 hover:text-white">&larr; Kembali</a>

    <div class="panel mb-6">
        <h1 class="text-2xl text-center mb-6 glow" style="color: <?= $themeStyles['primary_color'] ?>">
            Riwayat Pelatihan <?= htmlspecialchars($abra->getDisplayName()) ?>
        </h1>

        <?php if (empty($history)): ?>
            <div class="text-center py-12 text-gray-500">
                Belum ada sesi latihan.<br>
                <a href="train.php" class="underline mt-2 inline-block" style="color: <?= $themeStyles['primary_color'] ?>">
                    Mulai latihan sekarang!
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach (array_reverse($history) as $i => $log): ?>
                <div class="history-card p-4 rounded-lg">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-bold" style="color: <?= $themeStyles['primary_color'] ?>">
                            #<?= count($history) - $i ?>
                        </span>
                        <span class="text-gray-400"><?= htmlspecialchars($log['timestamp']) ?></span>
                    </div>
                    <div class="grid grid-cols-2 gap-y-1 text-xs">
                        <span>Jenis:</span> 
                        <span class="text-yellow-300"><?= htmlspecialchars($log['training_type']) ?></span>
                        
                        <span>Intensitas:</span> 
                        <span><?= $log['intensity'] ?></span>
                        
                        <span>Level:</span> 
                        <span><?= $log['level_before'] ?> → <span class="text-green-400"><?= $log['level_after'] ?></span></span>
                        
                        <span>HP:</span> 
                        <span><?= $log['hp_before'] ?> → <span style="color: <?= $themeStyles['primary_color'] ?>"><?= $log['hp_after'] ?></span></span>
                        
                        <span>XP Gain:</span> 
                        <span class="text-yellow-300">+<?= $log['xp_gain'] ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-8 text-center">
        <a href="index.php" class="py-2 px-6 btn-back rounded inline-block">
            Kembali ke Beranda
        </a>
    </div>
</div>
</body>
</html>