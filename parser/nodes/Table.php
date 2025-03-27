<?php

namespace Giuseppe\parser\nodes;

use \Giuseppe\parser\exceptions\LabelRefException;
use Giuseppe\parser\exceptions\TableRefException;

class Table extends Node
{

    protected $pluralName;

    protected $htmlFormLabel;

    protected $htmlListLabel;

    protected $id;

    protected $fields;

    protected $subTables;

    protected $subTablesO;

    public function getPluralName(): SnakeCaseProperty {
        return $this->pluralName;
    }

    public function setPluralName(SnakeCaseProperty $pluralName)
    {
        $this->pluralName = $pluralName;
    }

    public function setHtmlFormLabel(string $htmlFormLabel)
    {
        $this->htmlFormLabel = $htmlFormLabel;
    }

    /**
     * @return mixed
     */
    public function getHtmlFormLabel()
    {
        return $this->htmlFormLabel;
    }

    public function setHtmlListLabel(string $htmlListLabel)
    {
        $this->htmlListLabel = $htmlListLabel;
    }

    /**
     * @return mixed
     */
    public function getHtmlListLabel()
    {
        return $this->htmlListLabel;
    }

    public function setId(SnakeCaseProperty $id)
    {
        $this->id = $id;
    }

    public function getId(): SnakeCaseProperty
    {
        return $this->id;
    }

    public function addField(Field $field)
    {
        $this->fields[] = $field;
    }

    public function addSubtable(SnakeCaseProperty $subtable)
    {
        $this->subTables[] = $subtable;
    }

    public function addSubtableO(Table $subtable)
    {
        $this->subTablesO[] = $subtable;
    }

    private function removedDuplicates() {

        $result = [];
        foreach ($this->subTablesO as $subtableO) {

            $subtables = array_map(function($subtable) {
               return $subtable->getName()->getPropertyValue();
            }, $result);

            if (!in_array($subtableO->getName()->getPropertyValue(), $subtables)) {
                $result[] = $subtableO;
            }
        }

        return $result;
    }

    public function getSubtablesO() {

        return $this->subTablesO;
    }


    /**
     * @throws \parser\exceptions\LabelRefException
     */
    public function checkFieldLabelExists(string $fieldLabelName): bool
    {
        foreach ($this->fields as $field) {
            if ($field->getName()->getPropertyValue() === $fieldLabelName) {
                return true;
            }
        }
        throw new LabelRefException("Label ref error");
    }

    public function hasSubtables(): bool {
        return (null !== $this->subTables);
    }

    public function getSubtables() {
        return $this->subTables;
    }

    public function getFields() {
        return $this->fields;
    }

    public function getField(string $fieldName): Field
    {
        foreach ($this->fields as $field) {
            if ($field->getName()->getPropertyValue() === $fieldName) {
                return $field;
            }
        }
        throw new TableRefException("Table ref error");
    }

    public function getFieldsDifferentFrom(string $excludingField) {
        $result = [];
        foreach ($this->fields as $field) {
            if ($field->getName()->getPropertyValue() !== $excludingField) {
                $result[] = $field;
            }
        }
        return $result;
    }

    public function getFieldForStore() {
        $result = [];
        foreach ($this->fields as $field) {
            if (
                $field->getName()->getPropertyValue() !== $this->getId()->getPropertyValue() &&
                !$field->isMultipleSelect()
            ) {
                $result[] = $field;
            }
        }
        return $result;
    }


    function getSqlSelect() {

        $select = [];
        $index = 0;
        foreach ($this->fields as $field) {
            if ($field->getRef()) {
                if (!$field->isMultipleSelect()) {
                    $pn = $field->getRef()->getTableRefO()->getPluralName()->getPropertyValue() . "_" . $index;
                    array_push($select, $pn . "." . $field->getRef()->getLabelRef()->getPropertyValue() . " as " . $pn . "_" . $field->getRef()->getLabelRef()->getPropertyValue() . "_" . $index);
                    $index++;
                }
            } else {
                if ("datetime" === $field->getDataType()) {
                    array_push($select, "DATE_FORMAT(" . $this->getPluralName()->getPropertyValue() . "." . $field->getName()->getPropertyValue() . ", \"%d/%c/%Y %H:%i\") as " . $this->getPluralName()->getPropertyValue() . "_" . $field->getName()->getPropertyValue());

                } elseif ("date" === $field->getDataType()) {
                    array_push($select, "DATE_FORMAT(" . $this->getPluralName()->getPropertyValue() . "." . $field->getName()->getPropertyValue() . ", \"%d/%c/%Y\") as " . $this->getPluralName()->getPropertyValue() . "_" . $field->getName()->getPropertyValue());

                } elseif ("time" === $field->getDataType()) {
                    array_push($select, "DATE_FORMAT(" . $this->getPluralName()->getPropertyValue() . "." . $field->getName()->getPropertyValue() . ", \"%H:%i\") as " . $this->getPluralName()->getPropertyValue() . "_" . $field->getName()->getPropertyValue());

                } elseif ("boolean" === $field->getDataType()) {
                    $fieldName = $this->getPluralName()->getPropertyValue() . "." . $field->getName()->getPropertyValue();
                    $fieldLabel = $field->getLabel();
                    $as = $this->getPluralName()->getPropertyValue() . "_" . $field->getName()->getPropertyValue();
                    $case = "CASE WHEN $fieldName = 1 THEN \'$fieldLabel TRUE\' ELSE \'$fieldLabel FALSE\' END AS $as";
                    array_push($select, $case);

                } else {
                    array_push($select, $this->getPluralName()->getPropertyValue() . "." . $field->getName()->getPropertyValue() . " as " . $this->getPluralName()->getPropertyValue() . "_" . $field->getName()->getPropertyValue());

                }
            }
        }

        $out = $this->getPluralName()->getPropertyValue() . "." . $this->getId()->getPropertyValue() . ", " . implode(", ", $select);

        return $out;
    }


    public function getSelectField() {
        $result = [];
        foreach ($this->fields as $field) {
            if ("select" === $field->getHtmlType()) {
                $result[] = $field;
            }
        }
        return $result;
    }

    public function getMultipleSelectField() {
        $result = [];
        foreach ($this->fields as $field) {
            if ("select" === $field->getHtmlType() && $field->getRef()->getMultipleTableRef()) {
                $result[] = $field;
            }
        }
        return $result;
    }


    function getFK($ref) {
        $fk = null;
        foreach ($this->fields as $field) {
            if ($field->getRef() && $field->getRef()->getTableRef()->getPropertyValue() === $ref) {
                $fk = $field->getName()->getPropertyValue();
            }
        }
        return $fk;
    }


    function getFieldsUnique() {
        $fields = [];
        foreach ($this->getFields() as $field) {
            if ($field->getValidators() && in_array('notUnique', $field->getValidators())) {
                array_push($fields, $field);
            }
        }
        return $fields;
    }

    function getFieldsLabel($mainTable = null) {

        $filtered = [];
        foreach ($this->getFields() as $field0) {
            if ($field0->getRef()) {
                if ($field0->getRef()->getTableRef()->getPropertyValue() !== $mainTable->getName()->getPropertyValue()) {
                    $filtered[] = $field0;
                }
            } else {
                $filtered[] = $field0;
            }

        }

        return array_map(function($field) {
            return $field->getLabel();
        }, $filtered);

    }


    public function getSubtablesForImport() {

        $tables = [];

        foreach ($this->getSubtablesO() as $subtableO) {

            $tablesNames = array_map(function ($table) {
                return $table->getName()->getPropertyValue();
            }, $tables);

            if (!in_array($subtableO->getName()->getPropertyValue(), $tablesNames) &&
                $subtableO->getName()->getPropertyValue() !== $this->getName()->getPropertyValue())  {
                $tables[] = $subtableO;
            }
        }

        return $tables;
    }


    public function getTablesForFeArrayDefinitions() {

        $tables = [];


        foreach ($this->getSelectField() as $field) {

            $tablesNames = array_map(function ($table) {
                return $table->getName()->getPropertyValue();
            }, $tables);

            $tableRefO = $field->getRef()->getTableRefO();

            if (!in_array($tableRefO->getName()->getPropertyValue(), $tablesNames))  {
                $tables[] = $tableRefO;
            }

        }

        if ($this->hasSubtables()) {
            foreach ($this->getSubtablesO() as $subtableO) {

                foreach ($subtableO->getSelectField() as $field) {

                    $tablesNames = array_map(function ($table) {
                        return $table->getName()->getPropertyValue();
                    }, $tables);

                    $tableRefO = $field->getRef()->getTableRefO();

                    if (!in_array($tableRefO->getName()->getPropertyValue(), $tablesNames)) {
                        $tables[] = $tableRefO;
                    }

                }

            }

        }

        return $tables;

    }

    public function getTablesForFeServicesImport() {

        $tables = [];

        foreach ($this->getSelectField() as $field) {

            $tablesNames = array_map(function ($table) {
                return $table->getName()->getPropertyValue();
            }, $tables);

            $tableRefO = $field->getRef()->getTableRefO();

            if (!in_array($tableRefO->getName()->getPropertyValue(), $tablesNames) &&
                $tableRefO->getName()->getPropertyValue() !== $this->getName()->getPropertyValue())  {
                $tables[] = $tableRefO;
            }

        }

        if ($this->hasSubtables()) {
            foreach ($this->getSubtablesO() as $subtableO) {

                $tablesNames = array_map(function ($table) {
                    return $table->getName()->getPropertyValue();
                }, $tables);

                if (!in_array($subtableO->getName()->getPropertyValue(), $tablesNames) &&
                    $subtableO->getName()->getPropertyValue() !== $this->getName()->getPropertyValue()) {
                    $tables[] = $subtableO;
                }

                foreach ($subtableO->getSelectField() as $field) {

                    $tablesNames = array_map(function ($table) {
                        return $table->getName()->getPropertyValue();
                    }, $tables);

                    $tableRefO = $field->getRef()->getTableRefO();

                    if (!in_array($tableRefO->getName()->getPropertyValue(), $tablesNames) &&
                        $tableRefO->getName()->getPropertyValue() !== $this->getName()->getPropertyValue()) {
                        $tables[] = $tableRefO;
                    }

                }

            }

        }



        return $tables;



    }
    public  function getEagerLoding() {

        $eager = [];

        foreach ($this->getMultipleSelectField() as $field) {

            array_push($eager, $field->getRef()->getMultipleTableRefO()->getPluralName()->snakeToCamel());

        }

        $out = implode("', '", $eager);

        return "['" . $out . "']";

    }

    public  function getAppends() {

        $append = [];

        foreach ($this->getMultipleSelectField() as $field) {

            array_push($append, $field->getName()->getPropertyValue());

        }

        $out = implode("', '", $append);

        return "['" . $out . "']";

    }

}