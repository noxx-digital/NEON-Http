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
        $authority = '';

        if ( !empty( $user_info = $this->get_user_info() ) )
            $authority .= $user_info;

        if ( !empty( $user_info ) && !empty( $this->host ) )
            $authority .= '@';

        if ( !empty( $this->host ) )
            $authority .= $this->host;

        if ( !empty( $this->host ) && ( $this->port !== NULL ) )
            $authority .= ':' . $this->port;

        return $authority;
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
    public function with_scheme( $scheme ): Uri|UriInterface
    {
        $new = clone $this;
        $new->scheme = $scheme;
        return $new;
    }

    /**
     * @inheritDoc
     */
    public function with_user_info( $user, $password=NULL )
    {
        $new = clone $this;
        $new->user = $user;
        $new->pass = ( $password ) ? $password : '';
        return $new;
    }

    /**
     * @inheritDoc
     */
    public function with_host( $host )
    {
        $new = clone $this;
        $new->host = $host;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function with_port( $port )
    {
        $new = clone $this;
        $new->port = $port;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function with_path( $path )
    {
        $new = clone $this;
        $new->path = $path;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function with_query( $query )
    {
        $new = clone $this;
        $new->query = $query;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function with_fragment( $fragment )
    {
        $new = clone $this;
        $new->fragement = $fragment;
        return $this;
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