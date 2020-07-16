<?php

namespace App\Exceptions;


class InsufficientBalanceException extends  BaseException
{
    public function render()
    {
    	return ['errors' => 'errorr'];
    }
}
