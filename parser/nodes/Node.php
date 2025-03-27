<?php

namespace Giuseppe\parser\nodes;

class Node {

    protected $name;

    public function setName(SnakeCaseProperty $name) {
        $this->name = $name;
    }

    public function getName(): SnakeCaseProperty {
        return $this->name;
    }

    private $index;

    /**
     * @param mixed $index
     */
    public function setIndex($index): void
    {
        $this->index = $index;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

}