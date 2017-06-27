<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-26
 * Time: 16:45
 */

namespace ct\protocol;


interface IBase
{
    /**
     * @param $data
     * @return static
     */
    public static function decode($data);

    /**
     * @return string
     */
    public function encode();
}