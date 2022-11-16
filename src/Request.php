<?php

namespace Neon\Http;

class Request extends Message
{
    private static string $method;

    private static string $target;

    public function __construct()
    {
        if( function_exists( getallheaders() ))
        {
            parent::__construct( getallheaders(), $body );
        }
    }
}