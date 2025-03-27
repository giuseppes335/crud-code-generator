<?php

namespace Giuseppe\parser\nodes;

use \Giuseppe\parser\exceptions\HtmlTypeException;
use \Giuseppe\parser\exceptions\DataTypeException;
use \Giuseppe\parser\exceptions\ValidatorException;
class Field extends Node
{

    private $label;

    private $htmlType;

    private $dataType;

    private $ref;

    private $validators;

    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    /**
     * @throws \parser\exceptions\HtmlTypeException
     */
    public function setHtmlType(string $htmlType)
    {

        if ("text" === $htmlType ||
            "select" === $htmlType ||
            "date" === $htmlType ||
            "datetime-local" === $htmlType ||
            "time" === $htmlType ||
            "checkbox" === $htmlType ||
            "textarea" === $htmlType
        ) {
            $this->htmlType = $htmlType;
        } else {
            throw new HtmlTypeException("Html type error");
        }

    }

    public function getHtmlType(): string
    {
        return $this->htmlType;
    }

    /**
     * @throws \parser\exceptions\DataTypeException
     */
    public function setDataType(string $dataType)
    {

        if ("string" === $dataType ||
            "number" === $dataType ||
            "date" === $dataType ||
            "datetime" === $dataType ||
            "time" === $dataType ||
            "text" === $dataType ||
            "boolean" === $dataType ||
            "integer" === $dataType ||
            "float" === $dataType
        ) {
            $this->dataType = $dataType;
        } else {
            throw new DataTypeException("Data type error");
        }

    }

    public function getDataType(): string
    {
        return $this->dataType;
    }

    public function setRef(SelectRef $ref)
    {
        $this->ref = $ref;
    }

    public function getRef(): mixed  {
        return $this->ref;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @throws \parser\exceptions\ValidatorException
     */
    public function addValidator(string $validator)
    {
        if ("required" === $validator ||
            "maxlength" === $validator ||
            "notUnique" === $validator ||
            "pattern" === $validator
        ) {
            $this->validators[] = $validator;
        } else {
            throw new ValidatorException("Validator error");
        }

    }

    public function getValidators() {
        return $this->validators;
    }

    public function isRequired() {
        return $this->validators && in_array("required", $this->validators);
    }


    function getBeValidators($table, $updateId = false) {

        $validatorA = [];
        if($this->isRequired()) {
            if (!$this->isBoolean()) {
                array_push($validatorA, 'required');
            }

        } else {
            if (!$this->isMultipleSelect()) {
                array_push($validatorA, 'nullable');
            }

        }

        if ($this->getDataType() === 'string') {
            array_push($validatorA, 'string');
            array_push($validatorA, 'max:255');
        } elseif ($this->getDataType() === 'date') {
            array_push($validatorA, 'date_format:Y-m-d');
        } elseif ($this->getDataType() === 'datetime') {
            array_push($validatorA, 'date_format:Y-m-d\TH:i');
        } elseif ($this->getDataType() === 'time') {
            array_push($validatorA, 'date_format:H:i');
        } elseif ($this->getDataType() === 'integer') {
            array_push($validatorA, 'regex:/^\d{1,10}$/');
        } elseif ($this->getDataType() === 'float') {
            array_push($validatorA, 'regex:/^-?\d{0,3}([.]\d{0,5})?$/');
        } elseif ($this->getDataType() === 'boolean') {
            array_push($validatorA, 'boolean');
        } elseif ($this->getDataType() === 'etxt') {
            array_push($validatorA, 'text');
            array_push($validatorA, 'max:1000');
        }


        if ($this->ref) {
            $tableRef = $this->getRef()->getTableRefO()->getPluralName()->getPropertyValue();
            $id = $this->getRef()->getTableRefO()->getId()->getPropertyValue();
            array_push($validatorA, 'exists:' . $tableRef . ',' . $id);
        }


        if ($this->getValidators() && in_array('notUnique', $this->getValidators())) {
            if ($updateId) {
                array_push($validatorA, 'unique:' . $table->getPluralName()->getPropertyValue() . ',' . $this->getName()->getPropertyValue() . ', \' . ' . $updateId . ' . \',' . $table->getId()->getPropertyValue());
            } else {
                array_push($validatorA, 'unique:' . $table->getPluralName()->getPropertyValue() . ',' . $this->getName()->getPropertyValue());
            }
        }

        return implode('|', $validatorA);


    }


    function getFeValidators() {

        $v = [];

        if ($this->getValidators()) {
            foreach ($this->getValidators() as $validator) {
                if ($validator === 'required') {
                    if ($this->isBoolean()) {
                        $v[] = 'Validators.requiredTrue';
                    } else {
                        $v[] = 'Validators.required';
                    }

                } elseif ($validator === 'maxlength') {
                    if ("text" === $this->getDataType()) {
                        $v[] = 'Validators.maxLength(1000)';
                    } else {
                        $v[] = 'Validators.maxLength(255)';
                    }
                }
            }
        }

        if ("integer" === $this->getDataType()) {
            array_push($v, 'Validators.pattern(this.integerRegex)');
        } else if ("float" === $this->getDataType()) {
            array_push($v, 'Validators.pattern(this.decimalRegex)');
        }

        return $v;

    }

    function getFeAsyncValidators2($table) {

        if (!$this->getValidators()) {
            return '';
        }

        $async = '';
        foreach ($this->getValidators() as $validator) {
            if ($validator === 'notUnique') {
                $async = 'MyValidators.notUnique(this.' . $table->getName()->snakeToCamel() . 'Service, \'' . $this->getName()->getPropertyValue() . '\', \'id\', ' . $table->getName()->snakeToCamel() . '.' . $table->getId()->snakeToCamel() . '.toString())';
            }
        }
        return $async;
    }

    function getFeAsyncValidators($table) {

        if (!$this->getValidators()) {
            return '';
        }

        $async = '';
        foreach ($this->getValidators() as $validator) {
            if ($validator === 'notUnique') {
                $async = 'MyValidators.notUnique(this.' . $table->getName()->snakeToCamel() . 'Service, \'' . $this->getName()->getPropertyValue() . '\')';
            }
        }
        return $async;
    }

    function getValidatorsHtml() {

        if (!$this->getValidators()) {
            return [];
        }

        return array_map(function($validator) {
            return "'$validator'";
        }, $this->getValidators());

    }

    public function isMultipleSelect(): bool {
        return $this->getRef() && $this->getRef()->getMultipleTableRef();
    }

    public function isBoolean(): bool {
        return ("boolean" === $this->getDataType());
    }





}