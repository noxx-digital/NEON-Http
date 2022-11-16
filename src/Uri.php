<?php

namespace Neon\Http;

class Uri implements UriInterface
{
    private ?string $scheme;
    private ?string $user;
    private ?string $pass;
    private ?string $host;
    private ?int $port;
    private ?string $path;
    private ?string $query;
    private ?string $fragement;

	/**
	 * @param string $uri
	 */
    public function __construct( private string $uri )
    {
        $this->scheme       = ( $scheme = parse_url( $this->uri, PHP_URL_SCHEME )) ? strtolower( $scheme ) : '';
        $this->user         = ( $scheme = parse_url( $this->uri, PHP_URL_USER )) ? strtolower( $scheme ) : '';
        $this->pass         = ( $scheme = parse_url( $this->uri, PHP_URL_PASS )) ? $scheme : '';
        $this->host         = ( $scheme = strtolower( parse_url( $this->uri, PHP_URL_HOST ))) ? $scheme : '';
        $this->port         = parse_url( $this->uri, PHP_URL_PORT );
        $this->path         = ( $scheme = strtolower( parse_url( $this->uri, PHP_URL_PATH ))) ? $scheme : '';
        $this->query        = ( $scheme = strtolower( parse_url( $this->uri, PHP_URL_QUERY ))) ? $scheme : '';
        $this->fragement    = ( $scheme = strtolower( parse_url( $this->uri, PHP_URL_FRAGMENT ))) ? $scheme : '';
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
     * @inheritDoc
     */
    public function get_query(): string
    {
        $query = $this->query[0];
        if( $query === '&' )
            $query = substr( $query, 1 );
        return rawurlencode( $query );
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
    public function set_scheme( $scheme ): Uri|UriInterface
    {
        $this->scheme = $scheme;
    }

    /**
     * @inheritDoc
     */
    public function set_user_info( $user, $password=NULL ): Uri|UriInterface
    {
        $this->user = $user;
        $this->pass = ( $password ) ? $password : '';
    }

    /**
     * @inheritDoc
     */
    public function set_host( $host ): Uri|UriInterface|static
    {
       $this->host = $host;
    }

    /**
     * @inheritDoc
     */
    public function set_port( $port ): Uri|UriInterface|static
    {
        $this->port = $port;
    }

    /**
     * @inheritDoc
     */
    public function set_path( $path ): Uri|UriInterface|static
    {
        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function set_query( $query ): Uri|UriInterface|static
    {
        $this->query = $query;
    }

    /**
     * @inheritDoc
     */
    public function set_fragment( $fragment )
    {
        $this->fragement = $fragment;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        $uri = '';
        if( !empty( $this->get_scheme() ))
            $uri .= $this->get_scheme().':';

        if( !empty( $this->get_authority()  ))
            $uri .= '//'.$this->get_authority();

       return $uri.$this->get_path().$this->get_query().$this->get_fragment();
    }
}