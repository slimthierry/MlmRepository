<?php

namespace App\Exceptions;

use Exception;

class MemberNotBelongsToUser extends Exception
{
    public function render()
    {
    	return ['errors' => 'Member Not Belongs ToUser'];
    }
}
