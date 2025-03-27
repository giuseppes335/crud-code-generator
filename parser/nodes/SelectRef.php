<?php

namespace Giuseppe\parser\nodes;
class SelectRef {

    private $tableRef;

    private $labelRef;

    private $tableRefO;

    private $multipleTableRef;

    private $multipleTableRef0;

    public function setTableRef(SnakeCaseProperty $tableRef) {
        $this->tableRef = $tableRef;
    }

    public function setTableRefO(Table $tableRefO) {
        $this->tableRefO = $tableRefO;
    }

    public function setMultipleTableRef(SnakeCaseProperty $multipleTableRef) {
        $this->multipleTableRef = $multipleTableRef;
    }

    public function setMultipleTableRefO(Table $multipleTableRefO) {
        $this->multipleTableRef0 = $multipleTableRefO;
    }

    public function getMultipleTableRefO(): mixed {
        return $this->multipleTableRef0;
    }

    public function getMultipleTableRef(): mixed {
        return $this->multipleTableRef;
    }

    public function getTableRefO(): Table {
        return $this->tableRefO;
    }

    public function getTableRef(): SnakeCaseProperty
    {
        return $this->tableRef;
    }

    public function setLabelRef(SnakeCaseProperty $labelRef) {
        $this->labelRef = $labelRef;
    }

    public function getLabelRef(): SnakeCaseProperty
    {
        return $this->labelRef;
    }

    public function getMultipleRefId(string $tableName): mixed {

        if ($this->getMultipleTableRefO()) {

            foreach ($this->getMultipleTableRefO()->getFields() as $field) {

                if ($field->getRef() && $field->getRef()->getTableRef()->getPropertyValue() === $tableName) {

                    return $field->getName();

                }

            }

        }

    }

}