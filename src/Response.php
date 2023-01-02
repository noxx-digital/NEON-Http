<?php

namespace Neon\Http;

use Exception;

class Response extends Message
{
    private int $status_code;

    // https://httpwg.org/specs/rfc9110.html#overview.of.status.codes

    // [Informational 1xx]
    const HTTP_CONTINUE                        = 100;
    const HTTP_SWITCHING_PROTOCOLS             = 101;

    // [Successful 2xx]
    const HTTP_OK                              = 200;
    const HTTP_CREATED                         = 201;
    const HTTP_ACCEPTED                        = 202;
    const HTTP_NONAUTHORITATIVE_INFORMATION    = 203;
    const HTTP_NO_CONTENT                      = 204;
    const HTTP_RESET_CONTENT                   = 205;
    const HTTP_PARTIAL_CONTENT                 = 206;

    // [Redirection 3xx]
    const HTTP_MULTIPLE_CHOICES                = 300;
    const HTTP_MOVED_PERMANENTLY               = 301;
    const HTTP_FOUND                           = 302;
    const HTTP_SEE_OTHER                       = 303;
    const HTTP_NOT_MODIFIED                    = 304;
    const HTTP_USE_PROXY                       = 305;
    const HTTP_UNUSED                          = 306;
    const HTTP_TEMPORARY_REDIRECT              = 307;

    // [Client Error 4xx]
    const errorCodesBeginAt                    = 400;
    const HTTP_BAD_REQUEST                     = 400;
    const HTTP_UNAUTHORIZED                    = 401;
    const HTTP_PAYMENT_REQUIRED                = 402;
    const HTTP_FORBIDDEN                       = 403;
    const HTTP_NOT_FOUND                       = 404;
    const HTTP_METHOD_NOT_ALLOWED              = 405;
    const HTTP_NOT_ACCEPTABLE                  = 406;
    const HTTP_PROXY_AUTHENTICATION_REQUIRED   = 407;
    const HTTP_REQUEST_TIMEOUT                 = 408;
    const HTTP_CONFLICT                        = 409;
    const HTTP_GONE                            = 410;
    const HTTP_LENGTH_REQUIRED                 = 411;
    const HTTP_PRECONDITION_FAILED             = 412;
    const HTTP_REQUEST_ENTITY_TOO_LARGE        = 413;
    const HTTP_REQUEST_URI_TOO_LONG            = 414;
    const HTTP_UNSUPPORTED_MEDIA_TYPE          = 415;
    const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    const HTTP_EXPECTATION_FAILED              = 417;

    // [Server Error 5xx]
    const HTTP_INTERNAL_SERVER_ERROR           = 500;
    const HTTP_NOT_IMPLEMENTED                 = 501;
    const HTTP_BAD_GATEWAY                     = 502;
    const HTTP_SERVICE_UNAVAILABLE             = 503;
    const HTTP_GATEWAY_TIMEOUT                 = 504;
    const HTTP_VERSION_NOT_SUPPORTED           = 505;

    /**
     * @var array|string[]
     */
    static array $http_codes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        103 => 'Checkpoint',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended'
    );

    public function init()
    {
        parent::init();
        $this->status_code = 200;
    }

    /**
     * TODO make this nicer
     */
    public function get(): void
    {
        $headers = $this->get_headers();
        if( sizeof( $headers ) > 0 )
            foreach ( $headers as $name => $value )
                header( "$name: $value" );
    }

    /**
     * @param int $status_code
     * @param string $reason_phrase
     *
     * @return void
     * @throws StatusCodeExistsException
     * @throws Exception
     */
    public function register_status_code( int $status_code, string $reason_phrase ): void
    {
        if( !array_key_exists( $this->status_code, $this->http_codes ))
            $this->http_codes[$status_code] = $reason_phrase;
        else
            throw new Exception( 'Status code "'.$status_code.'" already exists.' );
    }

    /**
     * @param int $status_code
     *
     * @throws Exception
     */
    public function set_status_code( int $status_code ): void
    {
        if( array_key_exists( $this->status_code, self::$http_codes ))
        {
            $this->status_code = $status_code;
            http_response_code( $status_code );
        }
        else
            throw new Exception( 'Status code "'.$status_code.'" not registered.' );
    }

    /**
     * @return array
     */
    public function get_available_status_codes(): array
    {
        return $this->http_codes;
    }

    /**
     * @return int
     */
    public function get_status_code(): int
    {
        return $this->status_code;
    }

    /**
     * @return string
     */
    public function get_reason_phrase(): string
    {
        return $this->http_codes[$this->status_code];
    }
}

