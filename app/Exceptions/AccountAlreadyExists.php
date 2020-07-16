<?php


namespace App\Exceptions;


class AccountAlreadyExists extends BaseException
{
    public $message = 'Account already exists.';
}
