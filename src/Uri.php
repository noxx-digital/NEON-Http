<?php

namespace Neon\Http;

use function Neon\Util\dump;

class Uri
{
    private ?string $scheme;
    private ?string $user;
    private ?string $pass;
    private ?string $host;
    private ?string $port;
    private ?string $path;
    private ?string $query;
    private ?string $fragement;

	/**
	 * @param string $uri
	 */
    public function __construct( private readonly string $uri )
    {
        $this->scheme       = $this->parse_component( PHP_URL_SCHEME );
        $this->user         = $this->parse_component( PHP_URL_USER );
        $this->pass         = $this->parse_component( PHP_URL_PASS );
        $this->host         = $this->parse_component( PHP_URL_HOST );
        $this->port         = $this->parse_component( PHP_URL_PORT );
        $this->path         = $this->parse_component( PHP_URL_SCHEME );
        $this->query        = $this->parse_component( PHP_URL_QUERY );
        $this->fragement    = $this->parse_component( PHP_URL_FRAGMENT );
    }

    private function parse_component( int $component ): string
    {
        return ( $val = parse_url( $this->uri, $component )) ? strtolower( $val ) : '';
    }

    /**
     * @inheritDoc
     */
    public function get_scheme(): string
    {
        return $this->scheme;
    }

    /**
     * @inheritDoc
     */
    public function get_authority(): string
    {
        if( empty( $this->host ))
		{
			return '';
		}
        else
		{
			$authority = '';
			$user_info = $this->get_user_info();

			if ( !empty( $user_info ))
				$authority .= $user_info.'@';;

			$authority .=  $this->host;

			if( $this->port !== NULL )
            	$authority .= ':' . $this->port;

 			return $authority;
		}
    }
    /**
     * @inheritDoc
     */
    public function get_user_info(): string
    {
        if( !empty( $this->user ) && !empty( $this->pass ) )
            return $this->user.':'.$this->pass;

        else if( !empty( $this->user ) && empty( $this->pass ))
            return $this->user;

        else if( empty( $this->user ) && !empty( $this->pass ))
            return ':'.$this->pass;

        else
            return '';
    }

    /**
     * @inheritDoc
     */
    public function get_host(): string
    {
        return $this->host;
    }

    /**
     * @inheritDoc
     */
    public function get_port(): int
    {
        return $this->port;
    }

    /**
     * @inheritDoc
     */
    public function get_path(): string
    {
       return $this->path;
    }

    /**
     * @return string
     */
    public function get_query_string(): string
    {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function get_query_data(): array
    {
        $fields = explode( '&', $this->query );
        $query_arr = [];
        foreach ( $fields as $key => $val )
        {
            $tmp = explode( '=', $val );
            $query_arr[$tmp[0]] = $tmp[1];
        }
        return $query_arr;
    }

    /**
     * @inheritDoc
     */
    public function get_fragment(): string
    {
        return rawurlencode( $this->fragement );
    }

    /**
     * @inheritDoc
     */
    public function set_scheme( $scheme ): void
    {
        $this->scheme = $scheme;
    }

    /**
     * @inheritDoc
     */
    public function set_user_info( $user, $password=NULL ): void
    {
        $this->user = $user;
        $this->pass = ( $password ) ? $password : '';
    }

    /**
     * @inheritDoc
     */
    public function set_host( $host ): void
    {
       $this->host = $host;
    }

    /**
     * @inheritDoc
     */
    public function set_port( $port ): void
    {
        $this->port = $port;
    }

    /**
     * @inheritDoc
     */
    public function set_path( $path ): void
    {
        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function set_query( $query ): void
    {
        $this->query = $query;
    }

    /**
     * @inheritDoc
     */
    public function set_fragment( $fragment ): void
    {
        $this->fragement = $fragment;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        $uri = '';
        if( !empty( $this->get_scheme() ))
            $uri .= $this->get_scheme().':';

        if( !empty( $this->get_authority()  ))
            $uri .= '//'.$this->get_authority();

       return $uri.$this->get_path().$this->get_query().$this->get_fragment();
    }
}