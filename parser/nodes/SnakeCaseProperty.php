<?php

namespace Giuseppe\parser\nodes;

use \Giuseppe\parser\exceptions\SnakeCaseException;

class SnakeCaseProperty
{

    private $value;

    /**
     * @throws SnakeCaseException
     */
    public function __construct($value)
    {

        if (!is_string($value) || !preg_match("/^[_a-z0-9]+$/", $value)) {
            throw new SnakeCaseException("Snake case error");
        }

        $this->value = $value;

    }

    public function snakeToCamel()
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $this->value))));
    }

    public function snakeToCamelU()
    {
        return ucfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $this->value))));
    }

    public function snakeToDash()
    {
        return str_replace('_', '-', $this->value);
    }

    public function snakeToLabel() {
        return ucfirst(str_replace('_', ' ', $this->value));
    }

    public function getPropertyValue(): string
    {
        return $this->value;
    }

}