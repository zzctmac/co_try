<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-28
 * Time: 14:39
 */

namespace ct\protocol;


use ct\protocol\redis\MethodParserFactory;

class Redis implements IBase
{


    public $isPing = false;




    public $data;

    /**
     * Redis constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public static function create($data)
    {
        return new self($data);
    }


    /**
     * @param $data
     * @param Redis $requestProto
     * @param null $buf
     * @return mixed|null|static|[]
     */
    public static function decode($data, $requestProto = null, $buf = null)
    {

        $data = $buf . $data;
        $firstChar = substr($data, 0, 1);


        $handler = MethodParserFactory::getInstance($firstChar, $requestProto);
        if($handler == null)
            return null;
        return $handler->decode($data, $requestProto);
    }

    /**
     * @return string
     */
    public function encode()
    {
        if($this->isPing) {
            return "ping\r\n";
        }
        $msgArr = [];
        $msgArr[] = "*" . count($this->data);
        foreach ($this->data as $item)
        {
            $tmp = "$" . strlen($item);
            $msgArr[] = $tmp;
            $msgArr[] = $item;
        }
        $msgArr[] = "";
        return implode("\r\n", $msgArr);
    }
}