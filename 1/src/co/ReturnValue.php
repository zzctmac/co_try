<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-1
 * Time: 13:31
 */

namespace ct\co;


class ReturnValue
{
    protected $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }

    public static function create($value)
    {
        return new self($value);
    }
}