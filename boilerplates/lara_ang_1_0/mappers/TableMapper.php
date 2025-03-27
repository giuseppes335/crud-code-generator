<?php
namespace Giuseppe\boilerplates\lara_ang_1_0\mappers;

use \Giuseppe\parser\nodes\Table;
use \Giuseppe\boilerplates\lara_ang_1_0\mappers\AbstractMapper;

class TableMapper extends AbstractMapper {

    private $table;

    public function __construct(string $inputTemplateFile, string $outputDirectory, string $outputFile, Table $table) {
        parent::__construct($inputTemplateFile, $outputDirectory, $outputFile);
        $this->table = $table;
    }

    public function run() {

        $outputDirCheck = true;
        if (!file_exists($this->outputDirectory)) {
            $outputDirCheck = mkdir($this->outputDirectory, 0777, true);
        }

        if ($outputDirCheck) {

            global $table;
            $table = $this->table;

            ob_start();
            include($this->inputTemplateFile);
            $result = ob_get_clean();
            file_put_contents($this->outputDirectory . DIRECTORY_SEPARATOR . $this->outputFile, $result);
        }



    }
}