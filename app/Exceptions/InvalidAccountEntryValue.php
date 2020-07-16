<?php


namespace App\Exceptions;


class InvalidAccountEntryValue extends BaseException
{
    public $message = 'Account transaction entries must be a positive value';
}
