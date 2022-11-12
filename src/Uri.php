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
        $this->scheme       = ( $scheme = strtolower( parse_url( $this->uri, PHP_URL_SCHEME ))) ? $scheme : '';
        $this->user         = ( $scheme = strtolower( parse_url( $this->uri, PHP_URL_USER ))) ? $scheme : '';
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
    public function get_fragment()
    {
        return rawurlencode( $this->fragement );
    }

    /**
     * @inheritDoc
     */
    public function with_scheme( $scheme )
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function with_user_info( $user, $password = null )
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function with_host( $host )
    {
        // TODO: Implement with_host() method.
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function with_port( $port )
    {
        // TODO: Implement with_port() method.
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function with_path( $path )
    {
        // TODO: Implement with_path() method.
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function with_query( $query )
    {
        // TODO: Implement with_query() method.
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function with_fragment( $fragment )
    {
        // TODO: Implement with_fragment() method.
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
       return $this->get_scheme().'://'.$this->get_authority().$this->get_path().$this->get_query().$this->get_fragment();
    }
}