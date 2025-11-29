<?php
function getTheme() {
    if (!isset($_SESSION['theme'])) {
        $_SESSION['theme'] = [
            'background' => 'default',
            'color_scheme' => 'cyan',
            'font_size' => 'normal'
        ];
    }
    return $_SESSION['theme'];
}
function getThemeStyles() {
    $theme = getTheme();
    $backgrounds = [
        'default' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80',
        'ocean' => 'https://images.unsplash.com/photo-1505142468610-359e7d316be0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        'mountain' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        'sunset' => 'https://images.unsplash.com/photo-1495567720989-cebdbdd97913?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        'night' => 'https://images.unsplash.com/photo-1419242902214-272b3f66ee7a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        'city' => 'https://images.unsplash.com/photo-1514565131-fce0801e5785?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'
    ];
    $colorSchemes = [
        'cyan' => ['primary' => '#38bdf8', 'secondary' => '#0ea5e9'],
        'purple' => ['primary' => '#a855f7', 'secondary' => '#9333ea'],
        'green' => ['primary' => '#22c55e', 'secondary' => '#16a34a'],
        'orange' => ['primary' => '#f97316', 'secondary' => '#ea580c'],
        'pink' => ['primary' => '#ec4899', 'secondary' => '#db2777'],
        'yellow' => ['primary' => '#fbbf24', 'secondary' => '#f59e0b']
    ];
    $fontSizes = [
        'small' => '10px',
        'normal' => '12px',
        'large' => '14px'
    ];
    return [
        'background_url' => $backgrounds[$theme['background']],
        'primary_color' => $colorSchemes[$theme['color_scheme']]['primary'],
        'secondary_color' => $colorSchemes[$theme['color_scheme']]['secondary'],
        'font_size' => $fontSizes[$theme['font_size']]
    ];
}
function applyThemeToBody() {
    $styles = getThemeStyles();
    echo "
    <style>
        body {
            background: url('{$styles['background_url']}') no-repeat center center fixed;
            background-size: cover;
            font-size: {$styles['font_size']};
        }
        .panel {
            border-color: {$styles['primary_color']};
            box-shadow: 0 0 20px {$styles['primary_color']}33;
        }
        .text-theme { color: {$styles['primary_color']}; }
        .bg-theme { background: {$styles['primary_color']}; }
        .border-theme { border-color: {$styles['primary_color']}; }
    </style>
    ";
}
?>