<?php

function extractWordBefore($input, $ignore)
{
    return substr($input, 0, strpos($input, $ignore));
}

function sortByKey(&$array, $key, $order = 'asc')
{
    usort($array, function ($a, $b) use ($key, $order) {
        if (!isset($a[$key]) || !isset($b[$key])) {
            return 0;
        }

        if ($a[$key] == $b[$key]) {
            return 0;
        }

        if ($order === 'asc') {
            return ($a[$key] < $b[$key]) ? -1 : 1;
        } else {
            return ($a[$key] > $b[$key]) ? -1 : 1;
        }
    });
}

function lvp_translation($word, $translations = null)
{
    $_tt = explode('.', $word, );
    $_translations = $translations;
    $_tr = $translations;
    $locale = config('app.locale');
    if (!$translations) {
        $_tr = require __DIR__ . '/Translations/' . $locale . '.php';
    }

    foreach ($_tt as $key => $value) {
        $_tr = $_tr[$value];
    }

    return $_tr;
}

function getDirectNameAfterNamespace($namespace, $baseNamespace)
{
    // Retirer la partie de base du namespace
    $relativeNamespace = str_replace($baseNamespace, '', $namespace);
    // dd($baseNamespace, $relativeNamespace);
    // Convertir le reste du namespace en tableau en utilisant '\' comme délimiteur
    $parts = explode('\\', $relativeNamespace);
    // dd($parts);
    // Retourner le premier élément du tableau
    return $parts[1] ?? null;
}

function getNamespaceAndClassName(string $path): array
{
    // Divise le chemin en parties en utilisant '/' comme délimiteur
    $parts = explode('/', $path);

    // Récupère la dernière partie (le nom de la classe)
    $className = array_pop($parts);
    $namespace = '';
    if (count($parts) > 0) {
        $namespace = '\\' . implode('\\', $parts);
    }
    // Rejoint les parties restantes pour former le namespace

    return [$namespace, $className];
}
