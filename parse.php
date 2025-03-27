<?php

if (!$argv[1] || !$argv[2] || !$argv[3] || !$argv[4]) {
    return;
}

$jsonFile = $argv[1];

$timestamp = $argv[2];

$templateDirectory = $argv[3];

$beDirectory = $argv[4];

$feDirectory = $argv[5];

global $jsonSchema;

global $sessionId;

global $jsonSchemaParser;

global $inputTemplateDir;

global $generate;

global $tempDirectory;

if (file_exists($jsonFile)) {
    $jsonSchema = file_get_contents($jsonFile);
} else {
    echo 'Json file doesn\'t exists';
    die;
}

$sessionId = uniqid();

$tempDirectory = "temp2";

if (file_exists($templateDirectory)) {
    $inputTemplateDir = $templateDirectory;
} else {
    echo 'Template directory doesn\'t exists';
    die;
}

$generate = true;

if (!file_exists($beDirectory)) {
    echo 'Be directory doesn\'t exists';
    die;
}

if (!file_exists($feDirectory)) {
    echo 'Fe directory doesn\'t exists';
    die;
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}
if (file_exists( "." . DIRECTORY_SEPARATOR . $tempDirectory . DIRECTORY_SEPARATOR . $sessionId)) {
    deleteDirectory("." . DIRECTORY_SEPARATOR . $tempDirectory . DIRECTORY_SEPARATOR . $sessionId);
}

include 'parse_inner.php';

if ($jsonSchemaParser->jsonError()) {
    print_r($jsonSchemaParser->getErrors());
    die;
}

if (count($jsonSchemaParser->getErrors()) > 0) {
    print_r($jsonSchemaParser->getErrors());
    die;
}

if ($generate) {
    rcopy("." . DIRECTORY_SEPARATOR . $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "be", $beDirectory);
    rcopy("." . DIRECTORY_SEPARATOR . $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe", $feDirectory);
}


