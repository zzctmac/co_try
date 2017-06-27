<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-27
 * Time: 14:29
 */

namespace ct\route;


use Swoole\Http\Request;

interface IBase
{
    public function getController(Request $request);
}