<?php

namespace App\Exceptions;

use Exception;

class InvalidAccountException extends Exception
{
    public function render()
    {
    	return ['errors' => 'This Member or this Account don\'t exist!!!'];
    }
}
