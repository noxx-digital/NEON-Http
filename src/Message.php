<?php
	namespace Neon\Http;

    use Neon\Http\Stream;

	abstract class Message
	{
        private array $header;

		private string $protocol_version;

        private Stream $body;

		public function __construct( array $header=[], Stream $body=NULL )
		{
            if( sizeof( $header ) > 0 )
                $this->header = $header;

            if( $body )
                $this->body = $body;
            else
                $this->body = new Stream( 'r+' );

			$this->protocol_version = $_SERVER['SERVER_PROTOCOL'];
		}

		public function get_protocol_version(): string
		{
			return $this->protocol_version;
		}

		public function get_headers(): array
        {
			return $this->header;
		}

		public function has_header( $name ): bool
		{
			return key_exists( $name, $this->header );
		}

		public function get_header( string $name ): string
        {
			if( $this->has_header( $name ))
                return $this->header[$name];
            else
                return '';
		}

		public function get_header_line( string $name ): string
		{
            if( $this->has_header( $name ))
			    return $name.': '.$this->header[$name];
            else
                return '';
		}

		public function set_header( string $name, string $value ): void
		{
			$this->header[$name] = $value;
		}

		public function remove_header( string $name ): void
		{
            if( $this->has_header( $name ))
                unset( $this->header[$name] );
		}

		public function get_body(): Stream
		{
            return $this->body;
		}

        public function set_body( Stream $body ): void
        {
            $this->body = $body;
        }
	}