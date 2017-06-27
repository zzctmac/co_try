<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-26
 * Time: 16:46
 */

namespace ct\protocol;


class Simple implements IBase
{

    public $method;
    public $args = [];

    /**
     * Simple constructor.
     * @param $method
     * @param $args
     */
    public function __construct($method, $args)
    {
        $this->method = $method;
        $this->args = $args;
    }

    public static function create($method, $args = [])
    {
        return new self($method, $args);
    }


    public static function decode($data)
    {
        $arr = explode(",", trim($data));
        if(count($arr) <= 0) {
            throw new ProtocolException("param is less", -1);
        }
        $method = array_shift($arr);
        return self::create($method, $arr);

    }

    public  function encode()
    {
        $arr = $this->args;
        array_unshift($arr, $this->method);

        return implode(',', $arr) . "\r\n";
    }
}