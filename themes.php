<?php
require_once 'session.php';
require_once 'theme_helper.php';

if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = [
        'background' => 'default',
        'color_scheme' => 'cyan',
        'font_size' => 'normal'
    ];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['background'])) {
        $_SESSION['theme']['background'] = $_POST['background'];
    }
    if (isset($_POST['color_scheme'])) {
        $_SESSION['theme']['color_scheme'] = $_POST['color_scheme'];
    }
    if (isset($_POST['font_size'])) {
        $_SESSION['theme']['font_size'] = $_POST['font_size'];
    }
    header('Location: themes.php');
    exit;
}
$theme = $_SESSION['theme'];
$backgrounds = [
    'default' => [
        'name' => 'Default Futuristic',
        'url' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80',
        'icon' => '🤖'
    ],
    'ocean' => [
        'name' => 'Ocean Blue',
        'url' => 'https://images.unsplash.com/photo-1505142468610-359e7d316be0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        'icon' => '🌊'
    ],
    'mountain' => [
        'name' => 'Mountain Peak',
        'url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        'icon' => '⛰️'
    ],
    'sunset' => [
        'name' => 'Sunset Sky',
        'url' => 'https://images.unsplash.com/photo-1495567720989-cebdbdd97913?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        'icon' => '🌅'
    ],
    'night' => [
        'name' => 'Starry Night',
        'url' => 'https://images.unsplash.com/photo-1419242902214-272b3f66ee7a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        'icon' => '🌌'
    ],
    'city' => [
        'name' => 'Neon City',
        'url' => 'https://images.unsplash.com/photo-1514565131-fce0801e5785?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        'icon' => '🏙️'
    ]
];
$colorSchemes = [
    'cyan' => ['name' => 'Cyan', 'primary' => '#38bdf8', 'secondary' => '#0ea5e9', 'icon' => '💙'],
    'purple' => ['name' => 'Purple', 'primary' => '#a855f7', 'secondary' => '#9333ea', 'icon' => '💜'],
    'green' => ['name' => 'Green', 'primary' => '#22c55e', 'secondary' => '#16a34a', 'icon' => '💚'],
    'orange' => ['name' => 'Orange', 'primary' => '#f97316', 'secondary' => '#ea580c', 'icon' => '🧡'],
    'pink' => ['name' => 'Pink', 'primary' => '#ec4899', 'secondary' => '#db2777', 'icon' => '💗'],
    'yellow' => ['name' => 'Yellow', 'primary' => '#fbbf24', 'secondary' => '#f59e0b', 'icon' => '💛']
];
$fontSizes = [
    'small' => ['name' => 'Small', 'size' => '10px', 'icon' => '🔤'],
    'normal' => ['name' => 'Normal', 'size' => '12px', 'icon' => '🔠'],
    'large' => ['name' => 'Large', 'size' => '14px', 'icon' => '🔰']
];
$currentBg = $backgrounds[$theme['background']];
$currentColor = $colorSchemes[$theme['color_scheme']];
$currentFont = $fontSizes[$theme['font_size']];
$themeStyles = getThemeStyles();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tema Customisasi</title>
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
        .panel {
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            border: 2px solid <?= $themeStyles['primary_color'] ?>;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 0 20px <?= $themeStyles['primary_color'] ?>33;
        }
        .theme-card {
            background: rgba(30, 41, 59, 0.8);
            border: 2px solid transparent;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .theme-card:hover {
            transform: translateY(-5px);
            border-color: <?= $themeStyles['primary_color'] ?>;
        }
        .theme-card.active {
            border-color: <?= $themeStyles['primary_color'] ?>;
            background: rgba(<?= hexToRgb($themeStyles['primary_color']) ?>, 0.2);
        }
        .preview-box {
            width: 100%;
            height: 100px;
            border-radius: 8px;
            background-size: cover;
            background-position: center;
            margin-bottom: 8px;
        }
        .color-preview {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin: 0 auto 8px;
        }
        .btn-primary {
            background: linear-gradient(to right, <?= $themeStyles['primary_color'] ?>, <?= $themeStyles['secondary_color'] ?>);
        }
        .btn-primary:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body class="min-h-screen p-4">
<div class="max-w-6xl mx-auto">
    
    <a href="index.php" class="inline-block mb-4 text-sm text-gray-400">&larr; Kembali</a>
    <div class="panel mb-6">
        <h1 class="text-2xl text-center mb-6" style="color: <?= $themeStyles['primary_color'] ?>">
            Tema Customisasi
        </h1>

        <div class="mb-8 bg-gray-800/70 p-4 rounded-lg">
            <h3 class="text-sm font-bold mb-3" style="color: <?= $themeStyles['primary_color'] ?>">
                Current Theme
            </h3>
            <div class="grid grid-cols-3 gap-4 text-center text-xs">
                <div>
                    <div class="text-2xl mb-2"><?= $currentBg['icon'] ?></div>
                    <div class="text-gray-400">Background</div>
                    <div style="color: <?= $themeStyles['primary_color'] ?>"><?= htmlspecialchars($currentBg['name']) ?></div>
                </div>
                <div>
                    <div class="text-2xl mb-2"><?= $currentColor['icon'] ?></div>
                    <div class="text-gray-400">Color</div>
                    <div style="color: <?= $themeStyles['primary_color'] ?>"><?= htmlspecialchars($currentColor['name']) ?></div>
                </div>
                <div>
                    <div class="text-2xl mb-2"><?= $currentFont['icon'] ?></div>
                    <div class="text-gray-400">Font Size</div>
                    <div style="color: <?= $themeStyles['primary_color'] ?>"><?= htmlspecialchars($currentFont['name']) ?></div>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <h3 class="text-sm font-bold mb-4" style="color: <?= $themeStyles['primary_color'] ?>">
                Background Theme
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                <?php foreach ($backgrounds as $key => $bg): ?>
                <form method="POST">
                    <button type="submit" name="background" value="<?= $key ?>" 
                            class="theme-card w-full <?= $theme['background'] === $key ? 'active' : '' ?>">
                        <div class="preview-box" style="background-image: url('<?= $bg['url'] ?>')"></div>
                        <div class="text-center">
                            <div class="text-xl mb-1"><?= $bg['icon'] ?></div>
                            <div class="text-xs"><?= htmlspecialchars($bg['name']) ?></div>
                        </div>
                    </button>
                </form>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mb-8">
            <h3 class="text-sm font-bold mb-4" style="color: <?= $themeStyles['primary_color'] ?>">
                Color Scheme
            </h3>
            <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
                <?php foreach ($colorSchemes as $key => $color): ?>
                <form method="POST">
                    <button type="submit" name="color_scheme" value="<?= $key ?>" 
                            class="theme-card w-full <?= $theme['color_scheme'] === $key ? 'active' : '' ?>">
                        <div class="color-preview" style="background: linear-gradient(135deg, <?= $color['primary'] ?>, <?= $color['secondary'] ?>)"></div>
                        <div class="text-center">
                            <div class="text-xl mb-1"><?= $color['icon'] ?></div>
                            <div class="text-xs"><?= htmlspecialchars($color['name']) ?></div>
                        </div>
                    </button>
                </form>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-sm font-bold mb-4" style="color: <?= $themeStyles['primary_color'] ?>">
                Font Size
            </h3>
            <div class="grid grid-cols-3 gap-3">
                <?php foreach ($fontSizes as $key => $font): ?>
                <form method="POST">
                    <button type="submit" name="font_size" value="<?= $key ?>" 
                            class="theme-card w-full <?= $theme['font_size'] === $key ? 'active' : '' ?>">
                        <div class="text-2xl mb-2"><?= $font['icon'] ?></div>
                        <div class="text-center">
                            <div class="mb-1" style="font-size: <?= $font['size'] ?>">Sample Text</div>
                            <div class="text-xs"><?= htmlspecialchars($font['name']) ?></div>
                        </div>
                    </button>
                </form>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="text-center mt-6">
            <form method="POST" class="inline">
                <input type="hidden" name="background" value="default">
                <input type="hidden" name="color_scheme" value="cyan">
                <input type="hidden" name="font_size" value="normal">
                <button type="submit" class="py-2 px-6 bg-gray-700 rounded hover:bg-gray-600">
                    Reset to Default
                </button>
            </form>
        </div>

    </div>

    <div class="text-center">
        <a href="index.php" class="inline-block py-2 px-6 bg-gray-700 rounded hover:bg-gray-600">
            Kembali ke Beranda
        </a>
    </div>
</div>

<?php
function hexToRgb($hex) {
    $hex = str_replace('#', '', $hex);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return "$r, $g, $b";
}
?>
</body>
</html>