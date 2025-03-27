<?php

require __DIR__ . '/vendor/autoload.php';

use \Giuseppe\parser\nodes\App;
use \Giuseppe\parser\JsonSchemaParser;
use \Giuseppe\boilerplates\lara_ang_1_0\mappers\AppMapper;
use \Giuseppe\boilerplates\lara_ang_1_0\mappers\TableMapper;

function rcopy($src, $dst) {
    if (is_dir($src)) {
        @mkdir($dst);
        $files = scandir($src);
        foreach ($files as $file)
            if ($file != "." && $file != "..") rcopy("$src/$file", "$dst/$file");
    }
    else if (file_exists($src)) copy($src, $dst);
}

global $jsonSchema;

global $jsonErrors;

global $sessionId;

global $jsonSchemaParser;

global $inputTemplateDir;

global $generate;

global $timestamp;

global $tempDirectory;

$decodedSchema = json_decode($jsonSchema);

$jsonSchemaParserErrors = [];

$newApp = new App();

$jsonSchemaParser = new JsonSchemaParser($decodedSchema, $jsonSchemaParserErrors, $newApp);

if (!$jsonSchemaParser->jsonError()) {

    $jsonSchemaParser->checkDecodedSchema();
    $jsonErrors = $jsonSchemaParser->getErrors();

    if (count($jsonErrors) === 0 && $generate) {

        $routesApiMapper = new AppMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "php_routes.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "be" . DIRECTORY_SEPARATOR . "routes", "api.php", $newApp);
        $routesApiMapper->run();

        foreach ($newApp->getTables() as $index => $table) {
            $fileName = $timestamp . "_create_" . $index . "_" . $table->getName()->snakeToDash() . "_table.php";
            $migrationMapper = new TableMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "php_migration.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "be" . DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . "migrations", $fileName, $table);
            $migrationMapper->run();
        }

        foreach ($newApp->getTables() as $table) {
            $fileName = $table->getName()->snakeToCamelU() . ".php";
            $modelMapper = new TableMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "php_model.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "be" . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "Models", $fileName, $table);
            $modelMapper->run();
        }

        foreach ($newApp->getTables() as $table) {
            $fileName = $table->getName()->snakeToCamelU() . "Controller.php";
            $controllerMapper = new TableMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "php_controller.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "be" . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "Http" . DIRECTORY_SEPARATOR . "Controllers", $fileName, $table);
            $controllerMapper->run();
        }




        $routesAppMapper = new AppMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "ts_routes.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app", "app-routing.module.ts", $newApp);
        $routesAppMapper->run();

        $moduleAppMapper = new AppMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "ts_app_module.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app", "app.module.ts", $newApp);
        $moduleAppMapper->run();

        $serviceAppMapper = new AppMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "ts_app_service.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app", "app.service.ts", $newApp);
        $serviceAppMapper->run();

        $configurationAppMapper = new AppMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "ts_configuration.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app", "configuration.ts", $newApp);
        $configurationAppMapper->run();

        $configurationAppMapper = new AppMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "ts_my_validators.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app", "my-validators.ts", $newApp);
        $configurationAppMapper->run();

        $componentAppMapper = new AppMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "ts_app_component.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app", "app.component.ts", $newApp);
        $componentAppMapper->run();

        $componentHtmlAppMapper = new AppMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "html_app_component.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app", "app.component.html", $newApp);
        $componentHtmlAppMapper->run();



        foreach ($newApp->getTables() as $table) {
            $tableDashedName = $table->getName()->snakeToDash();
            $fileName = $table->getName()->snakeToDash() . "-form.component.ts";
            $tsFormMapper = new TableMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "ts_form.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . $tableDashedName . DIRECTORY_SEPARATOR . $tableDashedName . "-form", $fileName, $table);
            $tsFormMapper->run();
        }

        foreach ($newApp->getTables() as $table) {
            $tableDashedName = $table->getName()->snakeToDash();
            $fileName = $table->getName()->snakeToDash() . "-form.component.css";
            file_put_contents($tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . $tableDashedName . DIRECTORY_SEPARATOR . $tableDashedName . "-form" . DIRECTORY_SEPARATOR . $fileName, "");
        }

        foreach ($newApp->getTables() as $table) {
            $tableDashedName = $table->getName()->snakeToDash();
            $fileName = $table->getName()->snakeToDash() . "-form.component.html";
            $htmlFormMapper = new TableMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "html_form.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . $tableDashedName . DIRECTORY_SEPARATOR . $tableDashedName . "-form", $fileName, $table);
            $htmlFormMapper->run();
        }





        foreach ($newApp->getTables() as $table) {
            $tableDashedName = $table->getName()->snakeToDash();
            $fileName = $table->getName()->snakeToDash() . "-table.component.ts";
            $tsFormMapper = new TableMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "ts_table.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . $tableDashedName . DIRECTORY_SEPARATOR . $tableDashedName . "-table", $fileName, $table);
            $tsFormMapper->run();
        }

        foreach ($newApp->getTables() as $table) {
            $tableDashedName = $table->getName()->snakeToDash();
            $fileName = $table->getName()->snakeToDash() . "-table.component.css";
            file_put_contents($tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . $tableDashedName . DIRECTORY_SEPARATOR . $tableDashedName . "-table" . DIRECTORY_SEPARATOR . $fileName, "");
        }

        foreach ($newApp->getTables() as $table) {
            $tableDashedName = $table->getName()->snakeToDash();
            $fileName = $table->getName()->snakeToDash() . "-table.component.html";
            $tsFormMapper = new TableMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "html_table.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . $tableDashedName . DIRECTORY_SEPARATOR . $tableDashedName . "-table", $fileName, $table);
            $tsFormMapper->run();
        }




        foreach ($newApp->getTables() as $table) {
            $tableDashedName = $table->getName()->snakeToDash();
            $fileName = $table->getName()->snakeToDash() . ".module.ts";
            $tsModuleMapper = new TableMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "ts_module.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . $tableDashedName, $fileName, $table);
            $tsModuleMapper->run();
        }

        foreach ($newApp->getTables() as $table) {
            $tableDashedName = $table->getName()->snakeToDash();
            $fileName = $table->getName()->snakeToDash() . ".service.ts";
            $tsServiceMapper = new TableMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "ts_service.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . $tableDashedName, $fileName, $table);
            $tsServiceMapper->run();
        }

        foreach ($newApp->getTables() as $table) {
            $tableDashedName = $table->getName()->snakeToDash();
            $fileName = $table->getName()->snakeToDash() . ".ts";
            $tsInterfaceMapper = new TableMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "ts_interface.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . $tableDashedName, $fileName, $table);
            $tsInterfaceMapper->run();
        }


        foreach ($newApp->getTables() as $table) {
            $tableDashedName = $table->getName()->snakeToDash();
            $fileName = $table->getName()->snakeToDash() . "-routing.module.ts";
            $tsRoutingMapper = new TableMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "ts_routing.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . $tableDashedName, $fileName, $table);
            $tsRoutingMapper->run();
        }


        $routesApiMapper = new AppMapper($inputTemplateDir . DIRECTORY_SEPARATOR . "html_index.php", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId . DIRECTORY_SEPARATOR . "fe", "index.html", $newApp);
        $routesApiMapper->run();

        rcopy("boilerplates" . DIRECTORY_SEPARATOR ."lara_ang_1_0" . DIRECTORY_SEPARATOR . "statics" . DIRECTORY_SEPARATOR . "fe", $tempDirectory . DIRECTORY_SEPARATOR . $sessionId .DIRECTORY_SEPARATOR . "fe" . DIRECTORY_SEPARATOR . "app");

    }




}

