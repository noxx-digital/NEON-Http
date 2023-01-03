<?php

namespace Neon\Http;

// TODO Cookies, files

use Neon\Http\Uri;
use function Neon\Util\dump;

class Request extends Message
{
    private static $instance;

    /**
     * @var string
     */
    private string $method;

    /**
     * @var string
     */
    private string $target;

    /**
     * @var Uri
     */
    private Uri $uri;

    /**
     *
     */
    public function init()
    {
        if( function_exists( 'getallheaders' ))
        {
            parent::init(); // init stream with provided body
            parent::set_headers( getallheaders() );
            $this->method   = $_SERVER['REQUEST_METHOD'];
            $this->uri      = new Uri( $_SERVER['REQUEST_URI'] );
            $this->target   = $_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'];
        }
        else
        {
            throw \Exception( 'function getallheaders() not existing.' );
        }
    }

    /**
     * @return string
     */
    public function get_method(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function get_target(): string
    {
        return $this->target;
    }

    /**
     * @return Uri
     */
    public function get_uri(): Uri
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function get_query(): array
    {
        return $this->uri->get_query_data();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $this->print_body();
        return '';
    }
}