<?php

namespace Giuseppe\boilerplates\lara_ang_1_0\mappers;

abstract class AbstractMapper {

    protected $inputTemplateFile;

    protected $outputDirectory;

    protected $outputFile;

    public function __construct($inputTemplateFile, $outputDirectory, $outputFile) {

        $this->inputTemplateFile = $inputTemplateFile;
        $this->outputDirectory = $outputDirectory;
        $this->outputFile = $outputFile;

    }

    public abstract function run();

}