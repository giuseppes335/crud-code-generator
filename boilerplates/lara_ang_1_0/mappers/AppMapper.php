<?php
namespace Giuseppe\boilerplates\lara_ang_1_0\mappers;

use \Giuseppe\parser\nodes\App;
use \Giuseppe\boilerplates\lara_ang_1_0\mappers\AbstractMapper;

class AppMapper extends AbstractMapper
{

    private $app;

    public function __construct(string $inputTemplateFile, string $outputDirectory, string $outputFile, App $app) {
        parent::__construct($inputTemplateFile, $outputDirectory, $outputFile);
        $this->app = $app;
    }

    public function run() {

        $outputDirCheck = true;
        if (!file_exists($this->outputDirectory)) {
            $outputDirCheck = mkdir($this->outputDirectory, 0777, true);
        }

        if ($outputDirCheck) {

            global $app;
            $app = $this->app;

            ob_start();
            include($this->inputTemplateFile);
            $result = ob_get_clean();
            file_put_contents($this->outputDirectory . DIRECTORY_SEPARATOR . $this->outputFile, $result);
        }



    }
}