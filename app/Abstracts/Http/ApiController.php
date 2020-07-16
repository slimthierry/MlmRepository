<?php

namespace App\Abstracts\Http;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    // use Helpers;

    /**
     * Create the response for when a request fails validation.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {

    }
}
