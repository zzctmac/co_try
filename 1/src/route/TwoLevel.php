<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-27
 * Time: 14:30
 */

namespace ct\route;


use Swoole\Http\Request;

class TwoLevel implements IBase
{

    public function getController(Request $request)
    {
        $uri = $request->server['request_uri'];
        $actions = explode("/", $uri);
        array_shift($actions);
        if(isset($actions[0])) {
            $cname = $actions[0];
        } else {
            $cname = 'index';
        }

        if(isset($actions[1])) {
            $aname = $actions[1];
        } else {
            $aname = 'index';
        }

        $cclass = 'ct\\controller\\' . ucfirst(strtolower($cname));

        if(!class_exists($cclass)) {
            throw new RouteException("class not found", -1);
        }

        $crontroller = new $cclass();
        $afunc = ucfirst(strtolower($aname)) . 'Action';

        return [$crontroller, $afunc];

    }
}