<?php

function error_and_die(...$lines)
{
    foreach ($lines as $line) {
        echo "$line\n";
    }
    die(1);
}

if ($argc < 3) {
    error_and_die(
        'Error: Script requires at least 2 arguments',
        'Example: php set-environment relative-filename.yaml "DB_PASSWORD,password" "APP_DEBUG,true"'
    );
}

// Grab the filename from the first argument
$filename = $argv[1];
$file     = __DIR__ . "/$filename";

if (! is_readable($file)) {
    error_and_die('Error: Unable to find and read the app.yaml file. It should be a sibling of this file.');
}

$contents = file_get_contents($file);

for ($index = 2; $index < $argc; $index++) {
    $replacement = explode(',', $argv[$index]);

    if (2 !== count($replacement)) {
        error_and_die('Error: Invalid argument: ' . implode(',', $replacement));
    }

    [$search_for, $replace_with] = $replacement;
    $contents                    = str_replace("[$search_for]", $replace_with, $contents, $count);

    if ($count < 1) {
        error_and_die("Error: Did not find any instances of [$search_for]");
    }
}

if (false === file_put_contents(__DIR__ . "/../$filename", $contents)) {
    error_and_die('Failed to write to the app.yaml file in the parent directory');
}
