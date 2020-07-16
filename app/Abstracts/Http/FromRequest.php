<?php

namespace App\Abstracts\Http;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Support\Arr;

abstract class FormRequest extends BaseFormRequest
{
    /**
     * Determine if the given offset exists.
     *
     * @param  string  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return Arr::has(
            $this->route() ? $this->all() + $this->route()->parameters() : $this->all(),
            $offset
        );
    }
}
