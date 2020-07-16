<?php

namespace App\Exceptions;

class InvalidAccountMethod extends BaseException
{
    public $message = 'Account methods must be credit or debit';
}
