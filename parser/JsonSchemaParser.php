<?php

namespace Giuseppe\parser;

use Giuseppe\parser\nodes\Subtable;
use \Giuseppe\parser\nodes\Table;
use \Giuseppe\parser\nodes\SnakeCaseProperty;
use \Giuseppe\parser\nodes\Field;
use \Giuseppe\parser\nodes\SelectRef;
use \Giuseppe\parser\exceptions\SnakeCaseException;
use \Giuseppe\parser\exceptions\HtmlTypeException;
use \Giuseppe\parser\exceptions\DataTypeException;
use \Giuseppe\parser\exceptions\TableRefException;
use \Giuseppe\parser\exceptions\LabelRefException;
use \Giuseppe\parser\exceptions\ValidatorException;

class JsonSchemaParser {

    private $decodedSchema;

    private $jsonSchemaParserErrors;

    private $app;

    public function __construct($decodedSchema, $jsonSchemaParserErrors, $app) {
        $this->decodedSchema = $decodedSchema;
        $this->jsonSchemaParserErrors = $jsonSchemaParserErrors;
        $this->app = $app;
    }

    public function jsonError(): bool {
        $jsonLastError = json_last_error();
        if ($jsonLastError !== JSON_ERROR_NONE) {
            $this->jsonSchemaParserErrors[] = "Json error";
            return true;
        } else {
            return false;
        }
    }

    public function checkDecodedSchema() {

        // Check app_name
        if (!property_exists($this->decodedSchema, "app_name")) {
            $this->jsonSchemaParserErrors[] = "Json error - app_name required";
        } else {
            try {
                $this->app->setAppName($this->decodedSchema->app_name);
            } catch (TypeError $e) {
                $this->jsonSchemaParserErrors[] = "Json error - app_name must be of type string";
            }

        }

        // Check tables
        if (!property_exists($this->decodedSchema, "tables")) {
            $this->jsonSchemaParserErrors[] = "Json error - tables required";
        } else {

            if (!is_array($this->decodedSchema->tables)) {
                $this->jsonSchemaParserErrors[] = "Json error - tables must be an array";
            } else {

                /*
                if  (count($this->decodedSchema->tables) > 3) {
                    $this->jsonSchemaParserErrors[] = "Demo error - max 3 tables permitted";
                    return;
                }
                */

                foreach ($this->decodedSchema->tables as $tableIndex => $table) {

                    $newTable = new Table();
                    $this->app->addTable($newTable);

                    if (!property_exists($table, "name")) {
                        $this->jsonSchemaParserErrors[] = "Json error - name required at table " . $tableIndex;
                    } else {

                        try {
                            $tableName = new SnakeCaseProperty($table->name);
                            $newTable->setName($tableName);
                        } catch (SnakeCaseException $e) {
                            $this->jsonSchemaParserErrors[] = $e->getMessage() . " - name must be a snake case string at table " . $tableIndex;
                        }

                    }

                    if (!property_exists($table, "plural_name")) {
                        $this->jsonSchemaParserErrors[] = "Json error - plural_name required at table " . $tableIndex;
                    } else {

                        try {
                            $tablePluralName = new SnakeCaseProperty($table->plural_name);
                            $newTable->setPluralName($tablePluralName);
                        } catch (SnakeCaseException $e) {
                        } catch (SnakeCaseException $e) {
                            $this->jsonSchemaParserErrors[] = $e->getMessage() . " - plural_name must be a snake case string at table " . $tableIndex;
                        }

                    }

                    if (!property_exists($table, "html_form_label")) {
                        $this->jsonSchemaParserErrors[] = "Json error - html_form_label required at table " . $tableIndex;
                    } else {
                        try {
                            $newTable->setHtmlFormLabel($table->html_form_label);
                        } catch (TypeError $e) {
                            $this->jsonSchemaParserErrors[] = "Json error - html_form_label must be of type string at table " . $tableIndex;
                        }

                    }

                    if (!property_exists($table, "html_list_label")) {
                        $this->jsonSchemaParserErrors[] = "Json error - html_list_label required at table " . $tableIndex;
                    } else {
                        try {
                            $newTable->setHtmlListLabel($table->html_list_label);
                        } catch (TypeError $e) {
                            $this->jsonSchemaParserErrors[] = "Json error - html_list_label must be of type string at table " . $tableIndex;
                        }

                    }

                    if (!property_exists($table, "id")) {
                        $this->jsonSchemaParserErrors[] = "Json error - id required at table " . $tableIndex;
                    } else {

                        try {
                            $tableId = new SnakeCaseProperty($table->id);
                            $newTable->setId($tableId);
                        } catch (SnakeCaseException $e) {
                            $this->jsonSchemaParserErrors[] = $e->getMessage() . " - id must be a snake case string at table " . $tableIndex;
                        }

                    }


                    // Check tables
                    if (!property_exists($table, "fields")) {
                        $this->jsonSchemaParserErrors[] = "Json error - fields required at table " . $tableIndex;
                    } else {

                        if (!is_array($table->fields)) {
                            $this->jsonSchemaParserErrors[] = "Json error - fields must be an array at table " . $tableIndex;
                        } else {

                            foreach ($table->fields as $fieldIndex => $field) {

                                $newField = new Field();
                                $newTable->addField($newField);

                                if (!property_exists($field, "name")) {
                                    $this->jsonSchemaParserErrors[] = "Json error - name required at table " . $tableIndex . " field " . $fieldIndex;
                                } else {

                                    try {
                                        $fieldName = new SnakeCaseProperty($field->name);
                                        $newField->setName($fieldName);
                                    } catch (SnakeCaseException $e) {
                                        $this->jsonSchemaParserErrors[] = $e->getMessage() . " - name must be a snake case string at table " . $tableIndex . " field " . $fieldIndex;
                                    }

                                }


                                if (!property_exists($field, "label")) {
                                    $this->jsonSchemaParserErrors[] = "Json error - label required at table " . $tableIndex . " field " . $fieldIndex;
                                } else {
                                    try {
                                        $newField->setLabel($field->label);
                                    } catch (TypeError $e) {
                                        $this->jsonSchemaParserErrors[] = "Json error - label must be of type string at table " . $tableIndex . " field " . $fieldIndex;
                                    }

                                }


                                if (!property_exists($field, "html_type")) {
                                    $this->jsonSchemaParserErrors[] = "Json error - html_type required at table " . $tableIndex . " field " . $fieldIndex;
                                } else {

                                    try {
                                        $newField->setHtmlType($field->html_type);
                                    } catch (TypeError $e) {
                                        $this->jsonSchemaParserErrors[] = "Json error - html_type must be of type string at table " . $tableIndex . " field " . $fieldIndex;
                                    } catch (HtmlTypeException $e) {
                                        $this->jsonSchemaParserErrors[] = $e->getMessage() . " html_type must be 'text', 'select', 'date', 'datetime-local', 'checkbox' or 'textarea at table " . $tableIndex . " field " . $fieldIndex;
                                    }

                                }


                                if (!property_exists($field, "data_type")) {
                                    $this->jsonSchemaParserErrors[] = "Json error - data_type required at table " . $tableIndex . " field " . $fieldIndex;
                                } else {

                                    try {
                                        $newField->setDataType($field->data_type);
                                    } catch (TypeError $e) {
                                        $this->jsonSchemaParserErrors[] = "Json error - data_type must be of type string at table " . $tableIndex . " field " . $fieldIndex;
                                    } catch (DataTypeException $e) {
                                        $this->jsonSchemaParserErrors[] = $e->getMessage() . " data_type must be 'string', 'integer', 'float', 'boolean', 'text', 'date', 'datetime' or 'time' at table " . $tableIndex . " field " . $fieldIndex;
                                    }

                                }

                                if (property_exists($field, "validators")) {

                                    if (!is_array($field->validators)) {
                                        $this->jsonSchemaParserErrors[] = "Json error - validators must be an array at table " . $tableIndex . " field " . $fieldIndex;
                                    } else {

                                        foreach ($field->validators as $validator) {
                                            try {
                                                $newField->addValidator($validator);
                                            } catch (TypeError $e) {
                                                $this->jsonSchemaParserErrors[] = "Json error - validator must be of type string at table " . $tableIndex . " field " . $fieldIndex;
                                            } catch (ValidatorException $e) {
                                                $this->jsonSchemaParserErrors[] = $e->getMessage() . " validator must be 'required', 'maxlength', 'notUnique', 'integer' or 'float' at table " . $tableIndex . " field " . $fieldIndex;
                                            }

                                        }

                                    }

                                }


                            }

                        }
                    }



                }

            }

        }



        if (property_exists($this->decodedSchema, "tables") && count($this->jsonSchemaParserErrors) === 0) {

            foreach ($this->decodedSchema->tables as $tableIndex => $table) {

                $tableO = $this->app->getTable($table->name);

                if (property_exists($table, "subtables")) {

                    if (!is_array($table->subtables)) {
                        $this->jsonSchemaParserErrors[] = "Json error - subtables must be an array at table " . $tableIndex;
                    } else {

                        foreach ($table->subtables as $subtableIndex => $subtable) {

                            if (!property_exists($subtable, "name")) {
                                $this->jsonSchemaParserErrors[] = "Json error - name required at table " . $tableIndex . " subtable " . $subtableIndex;
                            } else {

                                if (!property_exists($subtable, "alias")) {
                                    $this->jsonSchemaParserErrors[] = "Json error - alias required at table " . $tableIndex . " subtable " . $subtableIndex;
                                } else {

                                    if (!property_exists($subtable, "inverse_ref")) {
                                        $this->jsonSchemaParserErrors[] = "Json error - inverse_ref required at table " . $tableIndex . " subtable " . $subtableIndex;
                                    } else {


                                        try {
                                            $this->app->checkTableExists($subtable->name);
                                            $subtableName = new SnakeCaseProperty($subtable->name);
                                            $alias = new SnakeCaseProperty($subtable->alias);
                                            $inverseRef = new SnakeCaseProperty($subtable->inverse_ref);
                                            $subtableTable = $this->app->getTable($subtable->name);

                                            $subtable0 = new Subtable($subtableTable);
                                            $subtable0->setAlias($alias);
                                            $subtable0->setInverseRef($inverseRef);
                                            $tableO->addSubtable($subtableName);
                                            $tableO->addSubtableO($subtable0);
                                        } catch (TableRefException $e) {
                                            $this->jsonSchemaParserErrors[] = $e->getMessage() . " - subtable " . $subtableIndex . " doesn't exists at table " . $tableIndex;
                                        } catch (SnakeCaseException $e) {
                                            $this->jsonSchemaParserErrors[] = $e->getMessage() . " - subtable " . $subtableIndex . "must be a snake case string at table " . $tableIndex;
                                        }

                                    }



                                }



                            }



                        }

                    }

                }

                if (property_exists($table, "fields")) {

                    foreach ($table->fields as $fieldIndex => $field) {

                        $fieldO = $tableO->getField($field->name);

                        if (property_exists($field, "html_type") && "select" === $field->html_type) {

                            if (!property_exists($field, "ref")) {
                                $this->jsonSchemaParserErrors[] = "Json error - ref required at table " . $tableIndex . " field " . $fieldIndex;
                            } else {
                                $newRef = new SelectRef();
                                $fieldO->setRef($newRef);

                                if (!property_exists($field->ref, "table_ref")) {
                                    $this->jsonSchemaParserErrors[] = "Json error - ref table_ref required at table " . $tableIndex . " field " . $fieldIndex;
                                } else {

                                    try {
                                        $this->app->checkTableExists($field->ref->table_ref);
                                        $tableRef = new SnakeCaseProperty($field->ref->table_ref);
                                        $newRef->setTableRef($tableRef);
                                        $newRef->setTableRefO($this->app->getTable($field->ref->table_ref));
                                    } catch (TableRefException $e) {
                                        $this->jsonSchemaParserErrors[] = $e->getMessage() . " - table_ref " . $field->ref->table_ref . " doesn't exists at table " . $tableIndex . " field " . $fieldIndex;
                                    } catch (SnakeCaseException $e) {
                                        $this->jsonSchemaParserErrors[] = $e->getMessage() . " - table_ref must be a snake case string at table " . $tableIndex . " field " . $fieldIndex;
                                    }

                                }

                                if (!property_exists($field->ref, "label_ref")) {
                                    $this->jsonSchemaParserErrors[] = "Json error - ref label_ref required at table " . $tableIndex . " field " . $fieldIndex;
                                } else {

                                    try {
                                        $tableRef0 = $this->app->getTable($field->ref->table_ref);
                                        $tableRef0->checkFieldLabelExists($field->ref->label_ref);

                                        $labelRef = new SnakeCaseProperty($field->ref->label_ref);
                                        $newRef->setLabelRef($labelRef);
                                    } catch (TableRefException $e) {
                                        $this->jsonSchemaParserErrors[] = $e->getMessage() . " - table_ref " . $field->ref->table_ref . " doesn't exists at table " . $tableIndex . " field " . $fieldIndex;
                                    } catch (LabelRefException $e) {
                                        $this->jsonSchemaParserErrors[] = $e->getMessage() . " - label_ref " . $field->ref->label_ref . " doesn't exists on " . $field->ref->table_ref . " at table " . $tableIndex . " field " . $fieldIndex;
                                    } catch (SnakeCaseException $e) {
                                        $this->jsonSchemaParserErrors[] = $e->getMessage() . " - label_ref must be a snake case string at table " . $tableIndex . " field " . $fieldIndex;
                                    }

                                }

                                if (property_exists($field->ref, "multiple_table_ref")) {
                                    try {
                                        $this->app->checkTableExists($field->ref->multiple_table_ref);
                                        $multipleTableRef = new SnakeCaseProperty($field->ref->multiple_table_ref);
                                        $newRef->setMultipleTableRef($multipleTableRef);
                                        $newRef->setMultipleTableRefO($this->app->getTable($field->ref->multiple_table_ref));
                                    } catch (TableRefException $e) {
                                        $this->jsonSchemaParserErrors[] = $e->getMessage() . " - multiple_table_ref " . $field->ref->multiple_table_ref . " doesn't exists at table " . $tableIndex . " field " . $fieldIndex;
                                    } catch (SnakeCaseException $e) {
                                        $this->jsonSchemaParserErrors[] = $e->getMessage() . " - multiple_table_ref must be a snake case string at table " . $tableIndex . " field " . $fieldIndex;
                                    }
                                }


                            }

                        }

                    }


                }

            }



        }
    }

    public function getErrors() {
        return $this->jsonSchemaParserErrors;
    }

}