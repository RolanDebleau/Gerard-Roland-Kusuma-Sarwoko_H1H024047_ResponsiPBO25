<?php
session_start();
require_once __DIR__ . '/classes/Abra.php';

if (!isset($_SESSION['pokemon'])) {
    $_SESSION['pokemon'] = serialize(new Abra());
    $_SESSION['training_history'] = [];
}
function getPokemon(): Abra {
    return unserialize($_SESSION['pokemon']);
}
function savePokemon(Abra $pokemon): void {
    $_SESSION['pokemon'] = serialize($pokemon);
}
if (isset($_GET['reset'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}