<?php

namespace Giuseppe\parser\nodes;

use Giuseppe\parser\nodes\Table;

class Subtable extends Table
{

    protected $alias;

    protected $inverseRef;

    public function __construct(Table $table) {

        $this->name = $table->getName();
        $this->pluralName = $table->getPluralName();
        $this->htmlFormLabel = $table->getHtmlFormLabel();
        $this->htmlListLabel = $table->getHtmlListLabel();
        $this->id = $table->getId();
        $this->fields = $table->getFields();

    }

    public function setAlias(SnakeCaseProperty $alias) {
        $this->alias = $alias;
    }

    public function getAlias(): SnakeCaseProperty {
        return $this->alias;
    }

    /**
     * @return mixed
     */
    public function getInverseRef()
    {
        return $this->inverseRef;
    }

    /**
     * @param mixed $inverseRef
     */
    public function setInverseRef($inverseRef): void
    {
        $this->inverseRef = $inverseRef;
    }

}