<?php

global $sessionId;

global $inputTemplateDir;

global $newApp;

require_once "parse_inner.php";
require_once "..\boilerplates\lara_ang_1_0\mappers\RoutesApiMapper.php";

$sessionId = uniqid();

$inputTemplateDir = "..\\boilerplates\\lara_ang_1_0\\templates";

$routesApiMapper = new AppMapper("$inputTemplateDir\\php_routes.php", "$sessionId\\be\\routes", $newApp);
$routesApiMapper->run();