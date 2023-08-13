<?php

namespace App\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;

class Stack
{

    private array $stack;


    public function __construct(){
        $this->stack = [];
    }

    public function push($item){
        if(is_null($item))
            throw new ErrorMsgException('push null');
        $this->stack[] = $item;
    }

    public function pushMany($items){
        if(is_null($items))
            throw new ErrorMsgException('push null');

        foreach ($items as $item){
            $this->stack[] = $item;
        }

    }

    public function pop(){
        if($this->isEmpty()){
            throw new ErrorMsgException('trying to pop from empty stack');
        }
        $lastItemIndex = array_key_last($this->stack);
        $data = $this->stack[$lastItemIndex];
        unset($this->stack[$lastItemIndex]);
        return $data;
    }

    public function isEmpty(){
        if($this->size())
            return false;
        return true;
    }

    public function size(){
        return count($this->stack);
    }

    /**
     * @note delete all items from the stack
     */
    public function flush(){
        $this->stack = [];
    }




}
