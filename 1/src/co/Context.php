<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-1
 * Time: 16:10
 */

namespace ct\co;



trait Context
{

    protected $co = null;

    /**
     * @param null $co
     */
    public function setCo($co)
    {
        $this->co = self::stackedCoroutine($co);
    }


    public static function stackedCoroutine(\Generator $gen) {
        $stack = new \SplStack;

        for (;;) {
            $value = $gen->current();

            if ($value instanceof \Generator) {
                $stack->push($gen);
                $gen = $value;
                continue;
            }

            $isReturnValue = $value instanceof ReturnValue;
            if (!$gen->valid() || $isReturnValue) {
                if ($stack->isEmpty()) {
                    return;
                }

                $gen = $stack->pop();
                $gen->send($isReturnValue ? $value->getValue() : NULL);
                continue;
            }

            $gen->send(yield $gen->key() => $value);
        }
    }

    public function runCoroutine($message = null)
    {


            if($message != null) {
                $this->co->send($message);

            }

            $value = $this->co->current();
            if($value instanceof ReturnValue) {
                return $value->getValue();
            }

            if(!$this->co->valid()) {
                return true;
            }




    }
}