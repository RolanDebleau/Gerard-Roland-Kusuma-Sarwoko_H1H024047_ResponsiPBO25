<?php
require_once 'session.php';
require_once 'theme_helper.php';
$abra = getPokemon();
$info = $abra->getInfo();
$stats = $abra->getDetailedStats();

$xpProgress = $info['xp_to_next_level'] > 0 
    ? min(100, ($info['xp'] / $info['xp_to_next_level']) * 100) 
    : 0;
$hpProgress = $abra->getMaxHp() > 0 
    ? min(100, ($info['hp'] / $abra->getMaxHp()) * 100) 
    : 0;
$themeStyles = getThemeStyles();
$unlockedSkills = $abra->getUnlockedSkills();
$nextSkill = $abra->getNextSkillToUnlock();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>POKÉCARE | <?= htmlspecialchars($abra->getDisplayName()) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Press Start 2P', monospace;
            background: url('<?= $themeStyles['background_url'] ?>') no-repeat center center fixed;
            background-size: cover;
            color: white;
            margin: 0;
            padding: 0;
            font-size: <?= $themeStyles['font_size'] ?>;
        }
        .glow { text-shadow: 0 0 8px rgba(255,255,255,0.7); }
        .neon-ring {
            animation: pulse 2s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 <?= $themeStyles['primary_color'] ?>b3; }
            70% { box-shadow: 0 0 0 10px <?= $themeStyles['primary_color'] ?>00; }
            100% { box-shadow: 0 0 0 0 <?= $themeStyles['primary_color'] ?>00; }
        }
        .stat-bar {
            height: 8px;
            background: #1e293b;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 2px;
        }
        .stat-fill {
            height: 100%;
            background: linear-gradient(to right, <?= $themeStyles['primary_color'] ?>, <?= $themeStyles['secondary_color'] ?>);
        }
        .panel {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            border: 2px solid <?= $themeStyles['primary_color'] ?>;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 0 20px <?= $themeStyles['primary_color'] ?>33;
        }
        .feature-btn {
            transition: all 0.3s;
        }
        .feature-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .skill-badge {
            background: rgba(<?= hexToRgb($themeStyles['primary_color']) ?>, 0.2);
            border: 1px solid <?= $themeStyles['primary_color'] ?>;
        }
        .skill-locked {
            background: rgba(100, 100, 100, 0.2);
            border: 1px solid #6b7280;
        }
    </style>
</head>
<body class="min-h-screen p-4">
<div class="max-w-6xl mx-auto">

    <div class="text-center mb-4 relative">
        <h1 class="text-3xl glow text-yellow-300">PokéCare Simulator</h1>
        <p class="text-xs text-gray-300">PRTC • Pokémon Research & Training Center</p>
        <a href="themes.php" class="absolute top-0 right-0 text-2xl" title="Customize Theme">🎨</a>
    </div>

    <div class="bg-black/70 backdrop-blur-sm rounded-xl p-4 mb-4 border shadow-lg" 
        style="border-color: <?= $themeStyles['primary_color'] ?>; box-shadow: 0 0 20px <?= $themeStyles['primary_color'] ?>33;">
        <div class="flex flex-col lg:flex-row gap-4 items-center">
            <div class="lg:w-1/3 bg-gray-800/50 p-4 rounded-xl">
                <h2 class="text-xl font-bold mb-2">Informasi Dasar</h2>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Nama:</label>
                    <div class="text-lg font-bold"><?= htmlspecialchars($abra->getDisplayName()) ?></div>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Tipe:</label>
                    <span class="inline-block px-3 py-1 bg-pink-600 rounded-full text-sm">
                        <?= htmlspecialchars($info['type']) ?>
                    </span>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">Level:</label>
                    <span class="text-green-400"><?= $info['level'] ?></span>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">XP:</label>
                    <div class="flex items-center">
                        <span><?= $info['xp'] ?> / <?= $info['xp_to_next_level'] ?></span>
                        <div class="ml-2 w-20 h-2 bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-yellow-500 to-orange-400"
                                style="width: <?= $xpProgress ?>%"></div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="block text-sm mb-1">HP:</label>
                    <div class="flex items-center">
                        <span><?= $info['hp'] ?> / <?= $abra->getMaxHp() ?></span>
                        <div class="ml-2 w-20 h-2 bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-green-500 to-cyan-400"
                                style="width: <?= $hpProgress ?>%"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-sm font-bold text-cyan-300 mb-1">Kelemahan:</h3>
                    <div class="space-y-1">
                        <?php foreach ($abra->getWeaknesses() as $weakness): ?>
                        <span class="inline-block px-2 py-1 bg-red-600 rounded text-xs"><?= htmlspecialchars($weakness) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="lg:w-1/3 flex flex-col justify-center items-center">
                <div class="relative">
                    <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?= $abra->getSpriteId() ?>.png"
                        alt="<?= htmlspecialchars($abra->getDisplayName()) ?>"
                        class="w-32 h-32 mx-auto drop-shadow-lg">
                    <div class="absolute inset-0 rounded-full border-4 neon-ring" 
                        style="border-color: <?= $themeStyles['primary_color'] ?>"></div>
                </div>
                <div class="text-center mt-2">
                    <div class="text-sm text-gray-400">No. <?= $abra->getPokedexNumber() ?></div>
                    <div class="text-sm text-yellow-300 font-bold"><?= htmlspecialchars($abra->getCategory()) ?></div>
                </div>
            </div>

            <div class="lg:w-1/3 bg-gray-800/50 p-4 rounded-xl">
                <h2 class="text-xl font-bold mb-2">Statistik</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Tinggi:</span>
                        <span><?= htmlspecialchars($stats['height']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Berat:</span>
                        <span><?= htmlspecialchars($stats['weight']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Kategori:</span>
                        <span><?= htmlspecialchars($stats['category']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Jenis Kelamin:</span>
                        <span><?= htmlspecialchars($stats['genderRatio']) ?></span>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-sm font-bold text-cyan-300 mb-1">Kemampuan:</h3>
                    <div class="space-y-1">
                        <?php foreach ($stats['abilities'] as $ability): ?>
                        <span class="inline-block px-2 py-1 bg-purple-600 rounded text-xs"><?= htmlspecialchars($ability) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 panel">
            <h2 class="text-xl font-bold mb-3">Statistik Detail</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <div class="text-xs">HP</div>
                    <div class="stat-bar"><div class="stat-fill" style="width: <?= $hpProgress ?>%"></div></div>
                    <div class="text-xs text-right"><?= $abra->getHp() ?> / <?= $abra->getMaxHp() ?></div>
                </div>
                <div>
                    <div class="text-xs">Serangan</div>
                    <div class="stat-bar"><div class="stat-fill" style="width: <?= min(100, ($abra->getAttack() / 150) * 100) ?>%"></div></div>
                    <div class="text-xs text-right"><?= $abra->getAttack() ?></div>
                </div>
                <div>
                    <div class="text-xs">Pertahanan</div>
                    <div class="stat-bar"><div class="stat-fill" style="width: <?= min(100, ($abra->getDefense() / 150) * 100) ?>%"></div></div>
                    <div class="text-xs text-right"><?= $abra->getDefense() ?></div>
                </div>
                <div>
                    <div class="text-xs">Serangan Khusus</div>
                    <div class="stat-bar"><div class="stat-fill" style="width: <?= min(100, ($abra->getSpecialAttack() / 150) * 100) ?>%"></div></div>
                    <div class="text-xs text-right"><?= $abra->getSpecialAttack() ?></div>
                </div>
                <div>
                    <div class="text-xs">Pertahanan Khusus</div>
                    <div class="stat-bar"><div class="stat-fill" style="width: <?= min(100, ($abra->getSpecialDefense() / 150) * 100) ?>%"></div></div>
                    <div class="text-xs text-right"><?= $abra->getSpecialDefense() ?></div>
                </div>
                <div>
                    <div class="text-xs">Kecepatan</div>
                    <div class="stat-bar"><div class="stat-fill" style="width: <?= min(100, ($abra->getSpeed() / 150) * 100) ?>%"></div></div>
                    <div class="text-xs text-right"><?= $abra->getSpeed() ?></div>
                </div>
            </div>
        </div>

        <div class="mt-6 panel">
            <h2 class="text-xl font-bold mb-3" style="color: <?= $themeStyles['primary_color'] ?>">
                Skills & Moves
            </h2>
            <?php if (!empty($unlockedSkills)): ?>
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-green-400 mb-2">✓ Unlocked Skills (<?= count($unlockedSkills) ?>):</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <?php foreach ($unlockedSkills as $levelReq => $skill): ?>
                        <div class="skill-badge p-2 rounded-lg">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-bold text-sm" style="color: <?= $themeStyles['primary_color'] ?>">
                                    <?= htmlspecialchars($skill['name']) ?>
                                </span>
                                <span class="text-xs text-gray-400">Lv.<?= $levelReq ?></span>
                            </div>
                            <div class="text-xs text-gray-300 mb-1"><?= htmlspecialchars($skill['description']) ?></div>
                            <div class="flex justify-between text-xs">
                                <span class="text-pink-400"><?= htmlspecialchars($skill['type']) ?></span>
                                <?php if ($skill['power'] > 0): ?>
                                    <span class="text-yellow-300">Power: <?= $skill['power'] ?></span>
                                <?php else: ?>
                                    <span class="text-gray-400">Status Move</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($nextSkill): ?>
                <div class="skill-locked p-3 rounded-lg">
                    <h3 class="text-sm font-bold text-gray-400 mb-2">🔒 Next Skill to Unlock:</h3>
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-bold text-gray-300"><?= htmlspecialchars($nextSkill['skill']['name']) ?></div>
                            <div class="text-xs text-gray-400"><?= htmlspecialchars($nextSkill['skill']['description']) ?></div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-400">Requires</div>
                            <div class="text-yellow-300 font-bold">Level <?= $nextSkill['level'] ?></div>
                            <div class="text-xs text-gray-400">
                                (<?= $nextSkill['level'] - $info['level'] ?> more levels)
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center text-green-400 p-3">
                    All skills unlocked! Your <?= htmlspecialchars($abra->getDisplayName()) ?> has mastered everything!
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-6 panel">
            <h2 class="text-xl font-bold mb-2">Deskripsi :</h2>
            <p class="text-sm"><?= htmlspecialchars($abra->getDescription()) ?></p>
            <div class="mt-4">
                <h3 class="text-sm font-bold text-cyan-300 mb-1">Jurus Spesial:</h3>
                <p class="text-sm"><?= htmlspecialchars($abra->specialMove()) ?></p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="train.php" class="feature-btn block bg-gradient-to-r from-blue-600 to-cyan-500 text-center py-3 rounded-lg shadow-md">
                <br>Latihan
            </a>
            <a href="history.php" class="feature-btn block bg-gradient-to-r from-purple-600 to-pink-500 text-center py-3 rounded-lg shadow-md">
                <br>Riwayat
            </a>
            <a href="stats.php" class="feature-btn block bg-gradient-to-r from-green-600 to-emerald-500 text-center py-3 rounded-lg shadow-md">
                <br>Analisa
            </a>
            <a href="themes.php" class="feature-btn block bg-gradient-to-r from-orange-600 to-yellow-500 text-center py-3 rounded-lg shadow-md">
                <br>Tema
            </a>
        </div>
    </div>
    <div class="mt-6 text-center">
        <a href="?reset" class="text-xs text-gray-400 hover:text-white">↻ Reset Session</a>
    </div>
</div>
</body>
</html>
<?php
function hexToRgb($hex) {
    $hex = str_replace('#', '', $hex);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return "$r, $g, $b";
}
?>