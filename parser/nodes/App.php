<?php

namespace Giuseppe\parser\nodes;

use \Giuseppe\parser\exceptions\TableRefException;
class App extends Node
{

    private $appName;

    private $tables;

    /**
     * @return mixed
     */
    public function getAppName()
    {
        return $this->appName;
    }

    public function setAppName(string $appName)
    {
        $this->appName = $appName;
    }

    public function addTable(Table $table)
    {
        $this->tables[] = $table;
    }

    /**
     * @throws \parser\exceptions\TableRefException
     */
    public function checkTableExists(string $tableName): bool
    {
        foreach ($this->tables as $table) {
            if ($table->getName()->getPropertyValue() === $tableName) {
                return true;
            }
        }
        throw new TableRefException("Table ref error");
    }

    /**
     * @throws \parser\exceptions\TableRefException
     */
    public function getTable(string $tableName): Table
    {
        foreach ($this->tables as $table) {
            if ($table->getName()->getPropertyValue() === $tableName) {
                return $table;
            }
        }
        throw new TableRefException("Table ref error");
    }

    public function getTables() {
        return $this->tables;
    }

    public function getTablesToImport() {

        $result = [];

        foreach ($this->tables as $table) {
            if (!in_array($table->getName()->getPropertyValue(), $result)) {
                $result[$table->getName()->getPropertyValue()] = $table;
            }
        }


        foreach ($this->tables as $table) {
            if ($table->hasSubtables()) {
                foreach ($table->getSubtables() as $subtable) {
                    if (!isset($result[$subtable->getPropertyValue()])) {
                        $result[$subtable->getPropertyValue()] = $this->getTable($subtable->getPropertyValue());
                    }
                }
            }
        }

        return $result;

    }

}