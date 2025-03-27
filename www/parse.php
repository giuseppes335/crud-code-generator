<?php

use Giuseppe\www\HZip;

session_start();

global $jsonSchema;

global $sessionId;

global $jsonSchemaParser;

global $inputTemplateDir;

global $generate;

global $timestamp;

global $tempDirectory;

require_once 'HZip.php';

$jsonSchema = $_POST["schema"];

$jsonErrors = [];

$sessionId = session_id();

$inputTemplateDir = ".." . DIRECTORY_SEPARATOR . "boilerplates" . DIRECTORY_SEPARATOR . "lara_ang_1_0" . DIRECTORY_SEPARATOR . "templates";

$generate = false;

$timestamp = date("Y_m_d_His");

$tempDirectory = ".." . DIRECTORY_SEPARATOR . "temp";


if (isset($_POST["submit"]) && "Validate and submit" === $_POST["submit"]) {
    $generate = true;
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
if (file_exists( $tempDirectory . DIRECTORY_SEPARATOR . $sessionId)) {
    deleteDirectory($tempDirectory . DIRECTORY_SEPARATOR . $sessionId);
}

include '..' . DIRECTORY_SEPARATOR . 'parse_inner.php';

if ($jsonSchemaParser->jsonError()) {
    $_SESSION["errors"] = [
        "schema" => [
            "Invalid schema"
        ]
    ];
    $_SESSION["old"] = [
        "schema" => $_POST["schema"]
    ];
    header("Location: /preprocessor.php");
    die();
}

if (count($jsonErrors) > 0) {
    $_SESSION["errors"] = [
        "schema" => $jsonErrors
    ];
    $_SESSION["old"] = [
        "schema" => $_POST["schema"]
    ];
    header("Location: /preprocessor.php");
    die();
}

if (isset($_POST["submit"]) && "Validate and format" === $_POST["submit"]) {
    $_SESSION["old"] = [
        "schema" => $_POST["schema"]
    ];
    header("Location: /preprocessor.php");
    die();
}

if ($generate) {
    if (file_exists( ".." . DIRECTORY_SEPARATOR . "zips" . DIRECTORY_SEPARATOR . $sessionId)) {
        deleteDirectory(".." . DIRECTORY_SEPARATOR . "zips" . DIRECTORY_SEPARATOR . $sessionId);
    }
    mkdir(".." . DIRECTORY_SEPARATOR . "zips" . DIRECTORY_SEPARATOR . $sessionId, 0777, true);
    HZip::zipDir($tempDirectory . DIRECTORY_SEPARATOR . $sessionId, ".." . DIRECTORY_SEPARATOR . "zips" . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "output.zip");

    chmod(".." . DIRECTORY_SEPARATOR . "zips" . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "output.zip", 0777);

    header("Content-Type: application/zip");
    header("Content-Disposition: attachment; filename=\"output.zip\"");

    readfile (".." . DIRECTORY_SEPARATOR . "zips" . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "output.zip");
    exit;
}








