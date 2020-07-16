<?php

namespace App\Exceptions;

class InvalidAccountException extends BaseException
{
    public function render()
    {
    	return ['errors' => 'This Member or this Account don\'t exist!!!'];
    }
}
