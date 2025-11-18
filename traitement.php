<?php
$sourceUrl = 'https://blog-investissement-immobilier.lybox.fr/contact.txt';
$localFile = __DIR__ . '/contact.txt';

if (!file_exists($localFile)) {
    $remoteData = file_get_contents($sourceUrl);
    if ($remoteData === false) {
        die;
    }
    file_put_contents($localFile, $remoteData);
}

$existing = file($localFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$existing = array_map('trim', $existing);

$contacts = ["Alice Dupont", "John Doe", "Jean Martin"];
$toAppend = [];

foreach ($contacts as $name) {
    if (!in_array($name, $existing, true)) {
        $toAppend[] = $name;
    }
}

if (!empty($toAppend)) {
    $data = implode(PHP_EOL, $toAppend) . PHP_EOL;
    file_put_contents($localFile, $data, FILE_APPEND);
    echo count($toAppend) . " contact(s) ajouté(s).\n";
} else {
    echo;
}
